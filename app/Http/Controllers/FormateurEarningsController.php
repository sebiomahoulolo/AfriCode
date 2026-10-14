<?php

namespace App\Http\Controllers;

use App\Models\FormateurEarning;
use App\Models\FormateurWithdrawal;
use App\Models\Payment;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FormateurEarningsController extends Controller
{
    /**
     * Dashboard des revenus
     */
    public function index()
    {
        $formateur = Auth::user();

        // Récupérer tous les IDs des cours du formateur
        $courseIds = Course::where('formateur_id', $formateur->id)->pluck('id');
        
        // Calculer les revenus en attente à partir des paiements réussis (77% pour le formateur)
        $totalPayments = 0;
        if ($courseIds->isNotEmpty()) {
            $totalPayments = Payment::where('payable_type', Course::class)
                ->whereIn('payable_id', $courseIds)
                ->where('status', 'succeeded')
                ->sum('amount');
        }
        $formateurShareFromPayments = $totalPayments * 0.77; // 77% pour le formateur
        
        // Calculer depuis formateur_earnings (si disponible)
        $earningsFromFormateurEarnings = FormateurEarning::where('formateur_id', $formateur->id)
            ->where('payment_status', 'paid')
            ->sum('total_earnings');
        
        // Utiliser la valeur la plus élevée
        $calculatedTotalEarnings = max($formateurShareFromPayments, $earningsFromFormateurEarnings);
        
        // Si aucune méthode ne fonctionne, utiliser la valeur stockée
        if ($calculatedTotalEarnings == 0) {
            $calculatedTotalEarnings = $formateur->total_earnings ?? 0;
        }
        
        // Calculer les retraits complétés (qui ont été déduits des revenus)
        $completedWithdrawals = FormateurWithdrawal::where('formateur_id', $formateur->id)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Calculer les retraits en attente ou en traitement (qui sont déjà réservés)
        $pendingWithdrawals = FormateurWithdrawal::where('formateur_id', $formateur->id)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');
        
        // Revenus en attente = Revenus totaux calculés - Retraits complétés - Retraits en attente
        $pendingEarnings = max(0, $calculatedTotalEarnings - $completedWithdrawals - $pendingWithdrawals);
        
        // Revenus totaux (tous les revenus gagnés, y compris ceux déjà retirés)
        $totalEarnings = $calculatedTotalEarnings;
        
        // Disponible pour retrait (doit être >= seuil minimum)
        $availableForWithdrawal = $pendingEarnings >= $formateur->withdrawal_threshold 
            ? $pendingEarnings 
            : 0;

        // Historique des revenus
        $earnings = FormateurEarning::where('formateur_id', $formateur->id)
            ->with('course') // Charger la relation course
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Retraits
        $withdrawals = FormateurWithdrawal::where('formateur_id', $formateur->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistiques par cours
        $courseStats = DB::table('formateur_earnings')
            ->select('courses.title', 'courses.id', 
                     DB::raw('SUM(formateur_earnings.total_earnings) as total'),
                     DB::raw('COUNT(formateur_earnings.id) as transactions'),
                     DB::raw('SUM(formateur_earnings.student_count) as students'))
            ->join('courses', 'formateur_earnings.course_id', '=', 'courses.id')
            ->where('formateur_earnings.formateur_id', $formateur->id)
            ->groupBy('courses.id', 'courses.title')
            ->get();

        return view('formateurs.earnings.index', compact(
            'formateur',
            'totalEarnings',
            'pendingEarnings',
            'availableForWithdrawal',
            'earnings',
            'withdrawals',
            'courseStats'
        ));
    }

    /**
     * Demander un retrait
     */
    public function requestWithdrawal(Request $request)
    {
        $formateur = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:' . $formateur->withdrawal_threshold,
            'payment_method' => 'required|in:mobile_money,card,bank_transfer',
            'payment_account' => 'required|string',
        ]);

        // Calculer les revenus en attente réels (comme dans index())
        $courseIds = Course::where('formateur_id', $formateur->id)->pluck('id');
        
        $totalPayments = 0;
        if ($courseIds->isNotEmpty()) {
            $totalPayments = Payment::where('payable_type', Course::class)
                ->whereIn('payable_id', $courseIds)
                ->where('status', 'succeeded')
                ->sum('amount');
        }
        $formateurShareFromPayments = $totalPayments * 0.77;
        
        $earningsFromFormateurEarnings = FormateurEarning::where('formateur_id', $formateur->id)
            ->where('payment_status', 'paid')
            ->sum('total_earnings');
        
        $calculatedTotalEarnings = max($formateurShareFromPayments, $earningsFromFormateurEarnings);
        if ($calculatedTotalEarnings == 0) {
            $calculatedTotalEarnings = $formateur->total_earnings ?? 0;
        }
        
        $completedWithdrawals = FormateurWithdrawal::where('formateur_id', $formateur->id)
            ->where('status', 'completed')
            ->sum('amount');
        
        $pendingWithdrawals = FormateurWithdrawal::where('formateur_id', $formateur->id)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');
        
        $realPendingEarnings = max(0, $calculatedTotalEarnings - $completedWithdrawals - $pendingWithdrawals);

        if ($realPendingEarnings < $request->amount) {
            return back()->with('error', 'Montant insuffisant. Revenus en attente : ' . number_format($realPendingEarnings, 0, ',', ' ') . ' FCFA');
        }

        // Créer la demande de retrait
        $withdrawal = FormateurWithdrawal::create([
            'formateur_id' => $formateur->id,
            'amount' => $request->amount,
            'currency' => 'XOF',
            'payment_method' => $request->payment_method,
            'payment_account' => $request->payment_account,
            'status' => 'pending',
        ]);

        // Réduire les revenus en attente
        $formateur->decrement('pending_earnings', $request->amount);

        // Notifier les administrateurs par email
        try {
            app(CourseMailService::class)->sendWithdrawalRequestToAdmins($withdrawal);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notification admin (demande retrait) non envoyée: ' . $e->getMessage());
        }

        return back()->with('success', 'Demande de retrait créée. Traitement sous 24h.');
    }

    /**
     * Détails d'un revenu
     */
    public function show($id)
    {
        $earning = FormateurEarning::with(['course', 'formateur'])
            ->where('formateur_id', Auth::id())
            ->findOrFail($id);

        return view('formateurs.earnings.show', compact('earning'));
    }
}

