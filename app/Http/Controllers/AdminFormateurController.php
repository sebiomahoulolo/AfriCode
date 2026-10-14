<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\FormateurDisciplinaryAction;
use App\Models\FormateurWithdrawal;
use App\Models\FormateurEarning;
use App\Events\CoursePublished;
use App\Services\CourseMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminFormateurController extends Controller
{
    /**
     * Liste des formateurs
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'formateur');

        // Filtres
        if ($request->has('status') && $request->status) {
            $query->where('formateur_status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $formateurs = $query->withCount('coursesInstructed')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);
        
        // Calculer le nombre réel d'étudiants uniques et les cours en attente de validation pour chaque formateur
        foreach ($formateurs as $formateur) {
            // Récupérer tous les IDs des cours du formateur
            $courseIds = Course::where('formateur_id', $formateur->id)->pluck('id');
            
            // Calculer le nombre d'étudiants uniques (distinct user_id) pour tous les cours du formateur
            if ($courseIds->isNotEmpty()) {
                $formateur->total_students = Enrollment::whereIn('course_id', $courseIds)
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                $formateur->total_students = 0;
            }
            
            // Calculer le nombre de cours en attente de validation
            $formateur->pending_validation_courses = Course::where('formateur_id', $formateur->id)
                ->where('validation_status', 'pending_validation')
                ->where('status', '!=', 'published')
                ->whereNull('validated_at')
                ->count();
            
            // Calculer le montant total des cours vendus (revenus du formateur)
            // Méthode 1 : Depuis les paiements (77% du montant total pour le formateur)
            if ($courseIds->isNotEmpty()) {
                $totalPayments = Payment::where('payable_type', Course::class)
                    ->whereIn('payable_id', $courseIds)
                    ->where('status', 'succeeded')
                    ->sum('amount');
                $formateur->total_revenue = $totalPayments * 0.77; // 77% pour le formateur
            } else {
                $formateur->total_revenue = 0;
            }
            
            // Méthode 2 : Depuis formateur_earnings (si disponible, prendre le maximum)
            $earningsFromFormateurEarnings = FormateurEarning::where('formateur_id', $formateur->id)
                ->where('payment_status', 'paid')
                ->sum('total_earnings');
            
            // Utiliser la valeur la plus élevée entre les deux méthodes
            $formateur->total_revenue = max($formateur->total_revenue, $earningsFromFormateurEarnings);
            
            // Si aucune méthode ne fonctionne, utiliser la valeur stockée dans le profil
            if ($formateur->total_revenue == 0) {
                $formateur->total_revenue = $formateur->total_earnings ?? 0;
            }
            
            // Calculer le montant des demandes de retrait en attente
            $formateur->pending_withdrawal_amount = FormateurWithdrawal::where('formateur_id', $formateur->id)
                ->whereIn('status', ['pending', 'processing'])
                ->sum('amount');
        }

        $stats = [
            'total' => User::where('role', 'formateur')->count(),
            'actifs' => User::where('role', 'formateur')->where('formateur_status', 'actif')->count(),
            'candidats' => User::where('role', 'formateur')->where('formateur_status', 'candidat')->count(),
            'suspendus' => User::where('role', 'formateur')->where('formateur_status', 'suspendu')->count(),
        ];

        return view('admin.formateurs.index', compact('formateurs', 'stats'));
    }

    /**
     * Détails d'un formateur
     */
    public function show($id)
    {
        $formateur = User::with([
            'coursesInstructed' => function($q) {
                $q->withCount('enrollments')
                  ->withAvg('ratings', 'rating');
            },
            'badges',
        ])->findOrFail($id);

        // Cours en attente de validation uniquement (non validés, non publiés)
        // Note: On filtre déjà par validation_status = 'pending_validation', donc les cours rejetés ou en correction sont automatiquement exclus
        $coursesPendingValidation = $formateur->coursesInstructed()
            ->where('validation_status', 'pending_validation')
            ->where('status', '!=', 'published') // Exclure les cours déjà publiés
            ->whereNull('validated_at') // Exclure les cours déjà validés (validated_at existe)
            ->withCount('enrollments')
            ->withAvg('ratings', 'rating')
            ->orderBy('submitted_for_validation_at', 'desc')
            ->get();

        // Statistiques
        $stats = [
            'total_courses' => $formateur->coursesInstructed()->count(),
            'published_courses' => $formateur->coursesInstructed()->where('status', 'published')->count(),
            'pending_validation' => $formateur->coursesInstructed()
                ->where('validation_status', 'pending_validation')
                ->where('status', '!=', 'published')
                ->whereNull('validated_at')
                ->count(),
            'total_students' => $formateur->coursesInstructed()->withCount('enrollments')->get()->sum('enrollments_count'),
            'total_earnings' => $formateur->total_earnings ?? 0,
            'pending_earnings' => $formateur->pending_earnings ?? 0,
            'average_rating' => $formateur->coursesInstructed()->withAvg('ratings', 'rating')->get()->avg('ratings_avg_rating') ?? 0,
        ];

        // Actions disciplinaires
        $disciplinaryActions = FormateurDisciplinaryAction::where('formateur_id', $id)
            ->orderBy('action_date', 'desc')
            ->get();

        // Retraits récents
        $withdrawals = FormateurWithdrawal::where('formateur_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.formateurs.show', compact('formateur', 'stats', 'disciplinaryActions', 'withdrawals', 'coursesPendingValidation'));
    }

    /**
     * Valider un formateur (candidat → actif)
     */
    public function approve($id)
    {
        $formateur = User::findOrFail($id);

        // Accepter les formateurs en statut candidat ou sans statut (null)
        if ($formateur->formateur_status !== 'candidat' && $formateur->formateur_status !== null) {
            return back()->with('error', 'Ce formateur n\'est pas en statut candidat. Statut actuel: ' . ($formateur->formateur_status ?? 'non défini'));
        }

        // Utiliser DB::table() pour mettre à jour directement
        DB::table('users')
            ->where('id', $id)
            ->update([
                'formateur_status' => 'actif',
                'contract_signed_at' => now(),
                'onboarding_completed' => true, // Marquer l'onboarding comme complété lors de la validation
                'onboarding_completed_at' => now(),
                'updated_at' => now()
            ]);

        // Envoyer un email au formateur pour l'informer que son compte est validé
        try {
            $courseMailService = app(CourseMailService::class);
            if ($courseMailService->sendFormateurAccountApproved($formateur)) {
                Log::info('Email « compte formateur validé » envoyé', ['formateur_id' => $formateur->id]);
            }
        } catch (\Throwable $e) {
            Log::error('Erreur envoi email formateur (compte validé): ' . $e->getMessage(), ['formateur_id' => $formateur->id]);
        }

        return back()->with('success', 'Formateur validé avec succès. Un email de confirmation a été envoyé au formateur.');
    }

    /**
     * Rejeter un formateur
     */
    public function reject($id, Request $request)
    {
        $formateur = User::findOrFail($id);

        $formateur->update([
            'formateur_status' => 'banni',
        ]);

        // Enregistrer l'action disciplinaire
        FormateurDisciplinaryAction::create([
            'formateur_id' => $id,
            'admin_id' => Auth::id(),
            'action_type' => 'ban',
            'reason' => 'Candidature rejetée',
            'details' => $request->reason ?? 'Candidature ne répondant pas aux critères',
            'action_date' => now(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Formateur rejeté.');
    }

    /**
     * Suspendre un formateur
     */
    public function suspend($id, Request $request)
    {
        $formateur = User::findOrFail($id);

        // Convertir en entier pour éviter l'erreur Carbon
        $days = (int) ($request->days ?? 7);
        $actionType = $days == 7 ? 'suspension_7d' : 'suspension_30d';

        // Utiliser DB::table() pour mettre à jour directement
        DB::table('users')
            ->where('id', $id)
            ->update([
                'formateur_status' => 'suspendu',
                'updated_at' => now()
            ]);

        FormateurDisciplinaryAction::create([
            'formateur_id' => $id,
            'admin_id' => Auth::id(),
            'action_type' => $actionType,
            'reason' => $request->reason ?? 'Suspension administrative',
            'details' => $request->details ?? '',
            'action_date' => now(),
            'expires_at' => now()->addDays($days),
            'is_active' => true,
        ]);

        return back()->with('success', "Formateur suspendu pour {$days} jours.");
    }

    /**
     * Lever une suspension
     */
    public function unsuspend($id)
    {
        $formateur = User::findOrFail($id);

        $formateur->update([
            'formateur_status' => 'actif',
        ]);

        // Résoudre les suspensions actives
        FormateurDisciplinaryAction::where('formateur_id', $id)
            ->where('is_active', true)
            ->whereIn('action_type', ['suspension_7d', 'suspension_30d'])
            ->update([
                'is_resolved' => true,
                'is_active' => false,
                'resolved_at' => now(),
            ]);

        return back()->with('success', 'Suspension levée.');
    }

    /**
     * Ajouter un avertissement
     */
    public function warn($id, Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'details' => 'nullable|string',
        ]);

        FormateurDisciplinaryAction::create([
            'formateur_id' => $id,
            'admin_id' => Auth::id(),
            'action_type' => 'warning',
            'reason' => $request->reason,
            'details' => $request->details,
            'action_date' => now(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Avertissement ajouté.');
    }

    /**
     * Valider un cours
     */
    public function validateCourse($courseId, Request $request)
    {
        $course = Course::findOrFail($courseId);

        if ($course->validation_status !== 'pending_validation') {
            return back()->with('error', 'Ce cours n\'est pas en attente de validation.');
        }

        // Valider le quality_score si fourni (doit être entre 0 et 100)
        if ($request->has('quality_score') && $request->quality_score !== null) {
            $qualityScore = (float) $request->quality_score;
            if ($qualityScore < 0 || $qualityScore > 100) {
                return back()->with('error', 'Le score de qualité doit être entre 0 et 100.');
            }
        }
        
        // Utiliser DB::table() pour mettre à jour directement et contourner le problème de fillable
        $updateData = [
            'validation_status' => 'approved',
            'status' => 'published',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
            'published_at' => now(),
            'updated_at' => now()
        ];
        
        if ($request->has('quality_score') && $request->quality_score !== null) {
            $updateData['quality_score'] = (float) $request->quality_score;
        }
        
        $updated = DB::table('courses')
            ->where('id', $courseId)
            ->update($updateData);
        
        if ($updated === false) {
            return back()->with('error', 'Erreur lors de la validation du cours. Veuillez réessayer.');
        }

        // Recharger le cours et déclencher l'événement pour envoyer les emails aux apprenants
        $course->refresh();
        CoursePublished::dispatch($course);

        return back()->with('success', 'Cours validé et publié.');
    }

    /**
     * Demander des corrections
     */
    public function requestRevision($courseId, Request $request)
    {
        $request->validate([
            'revision_notes' => 'required|string',
        ]);

        $course = Course::findOrFail($courseId);

        // Vérifier que le cours est en attente de validation
        if ($course->validation_status !== 'pending_validation') {
            return back()->with('error', 'Ce cours n\'est pas en attente de validation.');
        }

        // Utiliser l'assignation directe puis save() pour contourner le problème de fillable
        $course->validation_status = 'needs_revision';
        $course->revision_notes = $request->revision_notes;
        $course->status = 'draft'; // S'assurer que le statut reste en brouillon
        $course->save();

        return back()->with('success', 'Corrections demandées au formateur.');
    }

    /**
     * Rejeter un cours
     */
    public function rejectCourse($courseId, Request $request)
    {
        try {
            $request->validate([
                'admin_notes' => 'required|string',
            ]);

            $course = Course::findOrFail($courseId);

            // Vérifier que le cours est en attente de validation
            if ($course->validation_status !== 'pending_validation') {
                return back()->with('error', 'Ce cours n\'est pas en attente de validation. Statut actuel: ' . $course->validation_status);
            }

            // Utiliser DB::table() pour mettre à jour directement et contourner le problème de fillable
            $updated = DB::table('courses')
                ->where('id', $courseId)
                ->update([
                    'validation_status' => 'rejected',
                    'admin_notes' => $request->admin_notes,
                    'status' => 'draft',
                    'updated_at' => now()
                ]);
            
            if ($updated === false) {
                Log::error('Erreur lors de la mise à jour du cours rejeté', [
                    'course_id' => $courseId,
                    'admin_notes' => $request->admin_notes
                ]);
                return back()->with('error', 'Erreur lors du rejet du cours. Veuillez réessayer.');
            }

            \Log::info('Cours rejeté avec succès', [
                'course_id' => $courseId,
                'admin_id' => Auth::id()
            ]);

            return back()->with('success', 'Cours rejeté avec succès.');
        } catch (\Exception $e) {
            \Log::error('Exception lors du rejet du cours', [
                'course_id' => $courseId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Gérer les retraits
     */
    public function withdrawals(Request $request)
    {
        $query = FormateurWithdrawal::with('formateur');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.formateurs.withdrawals', compact('withdrawals'));
    }

    /**
     * Traiter un retrait (passe directement à "completed" - succès)
     */
    public function processWithdrawal($id, Request $request)
    {
        $withdrawal = FormateurWithdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Ce retrait a déjà été traité.');
        }

        // Passer directement à "completed" (succès) au lieu de "processing"
        $withdrawal->update([
            'status' => 'completed',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'processing_notes' => $request->notes,
            'payment_reference' => $request->payment_reference ?? 'AUTO-' . now()->format('YmdHis'),
        ]);

        // Mettre à jour les revenus du formateur
        $formateur = $withdrawal->formateur;
        $formateur->decrement('pending_earnings', $withdrawal->amount);
        $formateur->increment('total_earnings', $withdrawal->amount);

        return back()->with('success', 'Retrait validé et complété avec succès.');
    }

    /**
     * Compléter un retrait
     */
    public function completeWithdrawal($id, Request $request)
    {
        $withdrawal = FormateurWithdrawal::findOrFail($id);

        $request->validate([
            'payment_reference' => 'required|string',
        ]);

        $withdrawal->complete($request->payment_reference);

        // Mettre à jour les revenus du formateur
        $formateur = $withdrawal->formateur;
        $formateur->decrement('pending_earnings', $withdrawal->amount);
        $formateur->increment('total_earnings', $withdrawal->amount);

        return back()->with('success', 'Retrait complété.');
    }
}

