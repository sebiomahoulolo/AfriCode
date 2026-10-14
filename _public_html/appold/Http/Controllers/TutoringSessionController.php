<?php

namespace App\Http\Controllers;

use App\Models\TutoringSession;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Mentorship;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TutoringSessionController extends Controller
{
    /**
     * Display a listing of tutoring sessions.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'formateur') {
            // Vue formateur : ses sessions en tant que tuteur
            $sessions = TutoringSession::whereHas('tutor', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['student', 'tutor.user'])
                ->orderBy('start_time', 'desc')
                ->paginate(10);
                
            return view('tutoring.formateur.index', compact('sessions'));
        } else {
            // Vue apprenant : ses sessions en tant qu'étudiant principal ou participant
            $sessions = TutoringSession::where(function($query) use ($user) {
                    $query->where('student_id', $user->id)
                          ->orWhereHas('participants', function($q) use ($user) {
                              $q->where('user_id', $user->id);
                          });
                })
                ->with(['student', 'tutor.user', 'participants.user'])
                ->orderBy('start_time', 'desc')
                ->paginate(10);
                
            return view('tutoring.apprenant.index', compact('sessions'));
        }
    }

    /**
     * Show the form for creating a new tutoring session.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'formateur') {
            return redirect()->back()->with('error', 'Seuls les formateurs peuvent créer des sessions de tutorat.');
        }

        // Récupérer les mentorats actifs pour ce formateur
        $mentorships = Mentorship::where('mentor_id', $user->id)
            ->where('status', 'active')
            ->with('mentee')
            ->get();

        // Récupérer les apprenants qui ont fait des demandes
        $pendingRequests = Mentorship::where('mentor_id', $user->id)
            ->where('status', 'pending_mentor_approval')
            ->with('mentee')
            ->get();

        // Récupérer uniquement les apprenants inscrits aux cours du formateur si aucun mentorat n'est actif
        $allStudents = collect();
        if ($mentorships->isEmpty() && $pendingRequests->isEmpty()) {
            // Récupérer les IDs des cours du formateur
            $courseIds = $user->coursesInstructed()->pluck('id');
            
            if ($courseIds->isNotEmpty()) {
                // Récupérer les IDs des apprenants inscrits à ces cours
                $studentIds = Enrollment::whereIn('course_id', $courseIds)
                    ->distinct()
                    ->pluck('user_id');
                
                // Récupérer les apprenants actifs qui sont inscrits aux cours du formateur
                $allStudents = User::where('role', 'apprenant')
                    ->where('is_active', true)
                    ->whereIn('id', $studentIds)
                    ->get();
            }
        }

        return view('tutoring.formateur.create', compact('mentorships', 'pendingRequests', 'allStudents'));
    }

    /**
     * Store a newly created tutoring session.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un formateur
        if ($user->role !== 'formateur') {
            return redirect()->back()
                ->with('error', 'Seuls les formateurs peuvent créer des sessions de tutorat.');
        }

        // Vérifier que l'utilisateur a un profil tuteur
        $tutor = Tutor::where('user_id', $user->id)->first();
        if (!$tutor) {
            // Créer automatiquement un profil tuteur si il n'existe pas
            $tutor = Tutor::create([
                'user_id' => $user->id,
                'bio' => 'Formateur sur AfriCode',
                'expertise_areas' => ['Développement Web', 'Programmation'],
                'languages' => ['Français', 'Anglais'],
                'hourly_rate' => 5000, // 5000 FCFA par défaut
                'is_available' => true,
                'rating' => 0,
                'total_sessions' => 0
            ]);
        }

        // Filtrer les ressources et objectifs vides avant la validation
        $data = $request->all();
        
        if (isset($data['resources']) && is_array($data['resources'])) {
            $data['resources'] = array_filter($data['resources'], function($resource) {
                return !empty(trim($resource));
            });
            // Réindexer le tableau
            $data['resources'] = array_values($data['resources']);
        }
        
        if (isset($data['learning_objectives']) && is_array($data['learning_objectives'])) {
            $data['learning_objectives'] = array_filter($data['learning_objectives'], function($objective) {
                return !empty(trim($objective));
            });
            // Réindexer le tableau
            $data['learning_objectives'] = array_values($data['learning_objectives']);
        }

        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:one_on_one,group,workshop',
            'start_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:240',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
            'resources' => 'nullable|array',
            'resources.*' => 'required|url|max:500',
            'learning_objectives' => 'nullable|array',
            'learning_objectives.*' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ], [
            'title.required' => 'Le titre de la session est obligatoire.',
            'type.required' => 'Veuillez sélectionner un type de session.',
            'type.in' => 'Le type de session sélectionné n\'est pas valide.',
            'start_time.required' => 'Veuillez sélectionner une date et une heure pour la session.',
            'start_time.date' => 'La date sélectionnée n\'est pas valide.',
            'start_time.after' => 'La date et l\'heure de la session doivent être dans le futur.',
            'duration_minutes.required' => 'Veuillez sélectionner une durée pour la session.',
            'duration_minutes.integer' => 'La durée doit être un nombre entier.',
            'duration_minutes.min' => 'La durée minimum est de 30 minutes.',
            'duration_minutes.max' => 'La durée maximum est de 240 minutes (4 heures).',
            'participants.required' => 'Veuillez sélectionner au moins un participant.',
            'participants.array' => 'Les participants doivent être sélectionnés sous forme de liste.',
            'participants.min' => 'Veuillez sélectionner au moins un participant.',
            'participants.*.exists' => 'Un ou plusieurs participants sélectionnés n\'existent pas.',
            'resources.*.required' => 'Les ressources ne peuvent pas être vides.',
            'resources.*.url' => 'Les ressources doivent être des URLs valides.',
            'learning_objectives.*.required' => 'Les objectifs d\'apprentissage ne peuvent pas être vides.',
            'learning_objectives.*.string' => 'Les objectifs d\'apprentissage doivent être du texte.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        // Calculer l'heure de fin
        $startTime = \Carbon\Carbon::parse($request->start_time);
        $durationMinutes = (int) $request->duration_minutes;
        $endTime = $startTime->copy()->addMinutes($durationMinutes);
        
        // Calculer le prix
        $price = $tutor->hourly_rate * ($durationMinutes / 60);

        // Générer un code de session unique
        $sessionCode = $this->generateSessionCode();

        // Créer la session
        $session = TutoringSession::create([
            'tutor_id' => $tutor->id,
            'student_id' => $request->participants[0], // Premier participant comme étudiant principal
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'scheduled',
            'type' => $request->type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
            'price' => $price,
            'meeting_link' => $this->generateMeetingLink($sessionCode),
            'session_code' => $sessionCode,
            'notes' => $request->notes ?? null
        ]);

        // Ajouter les participants supplémentaires
        if (count($request->participants) > 1) {
            foreach (array_slice($request->participants, 1) as $participantId) {
                $session->participants()->create([
                    'user_id' => $participantId,
                    'joined_at' => null
                ]);
            }
        }

        // Ajouter les ressources (utiliser les données déjà filtrées)
        if (!empty($data['resources']) && is_array($data['resources'])) {
            foreach ($data['resources'] as $resource) {
                $session->sessionResources()->create([
                    'title' => $resource,
                    'type' => 'link',
                    'url' => $resource,
                    'description' => $resource
                ]);
            }
        }

        // Ajouter les objectifs d'apprentissage (utiliser les données déjà filtrées)
        if (!empty($data['learning_objectives']) && is_array($data['learning_objectives'])) {
            foreach ($data['learning_objectives'] as $objective) {
                $session->learningObjectives()->create([
                    'objective' => $objective
                ]);
            }
        }

        // Notifier les participants
        try {
            $this->notifyParticipants($session, $request->participants);
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création
            \Log::warning('Erreur lors de la notification des participants: ' . $e->getMessage());
        }

        return redirect()->route('tutoring.sessions.index')
            ->with('success', 'Session de tutorat créée avec succès ! Code: ' . $sessionCode);
    }

    /**
     * Display the specified tutoring session.
     */
    public function show(TutoringSession $session)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a accès à cette session
        if ($session->tutor->user_id !== $user->id && 
            $session->student_id !== $user->id && 
            !$session->participants()->where('user_id', $user->id)->exists()) {
            abort(403, 'Accès non autorisé à cette session.');
        }

        $session->load(['tutor.user', 'student', 'participants.user', 'resources', 'learningObjectives']);

        return view('tutoring.show', compact('session'));
    }

    /**
     * Join a tutoring session.
     */
    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_code' => 'required|string|size:8'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Code de session invalide.');
        }

        $session = TutoringSession::where('session_code', $request->session_code)
            ->where('status', 'scheduled')
            ->first();

        if (!$session) {
            return redirect()->back()
                ->with('error', 'Session non trouvée ou déjà commencée.');
        }

        // Vérifier que la session n'a pas encore commencé
        if ($session->start_time > now()) {
            return redirect()->back()
                ->with('error', 'La session n\'a pas encore commencé.');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur est autorisé à rejoindre
        $isAuthorized = $session->student_id === $user->id || 
                       $session->participants()->where('user_id', $user->id)->exists();

        if (!$isAuthorized) {
            return redirect()->back()
                ->with('error', 'Vous n\'êtes pas autorisé à rejoindre cette session.');
        }

        // Marquer l'utilisateur comme ayant rejoint
        if ($session->student_id !== $user->id) {
            $participant = $session->participants()->where('user_id', $user->id)->first();
            if ($participant && !$participant->joined_at) {
                $participant->update(['joined_at' => now()]);
            }
        }

        return redirect()->route('tutoring.sessions.join-room', $session)
            ->with('success', 'Vous avez rejoint la session avec succès.');
    }

    /**
     * Join the video conference room.
     */
    public function joinRoom(TutoringSession $session)
    {
        $user = Auth::user();
        
        // Vérifier l'accès
        if ($session->tutor->user_id !== $user->id && 
            $session->student_id !== $user->id && 
            !$session->participants()->where('user_id', $user->id)->exists()) {
            abort(403, 'Accès non autorisé à cette session.');
        }

        $session->load([
            'tutor.user', 
            'student', 
            'participants.user',
            'messages.user',
            'messages.attachments',
            'sessionResources'
        ]);

        return view('tutoring.room', compact('session'));
    }

    /**
     * Start a tutoring session.
     */
    public function start(TutoringSession $session)
    {
        $user = Auth::user();
        
        if ($session->tutor->user_id !== $user->id) {
            abort(403, 'Seul le tuteur peut démarrer cette session.');
        }

        if ($session->status !== 'scheduled') {
            return redirect()->back()
                ->with('error', 'Cette session ne peut pas être démarrée.');
        }

        $session->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Session démarrée avec succès.');
    }

    /**
     * Complete a tutoring session.
     */
    public function complete(Request $request, TutoringSession $session)
    {
        $user = Auth::user();
        
        if ($session->tutor->user_id !== $user->id) {
            abort(403, 'Seul le tuteur peut terminer cette session.');
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
            'feedback' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => $request->notes,
            'feedback' => $request->feedback
        ]);

        return redirect()->back()
            ->with('success', 'Session terminée avec succès.');
    }

    /**
     * Cancel a tutoring session.
     */
    public function cancel(Request $request, TutoringSession $session)
    {
        $user = Auth::user();
        
        if ($session->tutor->user_id !== $user->id && $session->student_id !== $user->id) {
            abort(403, 'Vous ne pouvez pas annuler cette session.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $session->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason
        ]);

        return redirect()->back()
            ->with('success', 'Session annulée avec succès.');
    }

    /**
     * Send a message in the session.
     */
    public function sendMessage(Request $request, TutoringSession $session)
    {
        $user = Auth::user();
        
        // Vérifier l'accès
        if ($session->tutor->user_id !== $user->id && 
            $session->student_id !== $user->id && 
            !$session->participants()->where('user_id', $user->id)->exists()) {
            abort(403, 'Accès non autorisé à cette session.');
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $message = $session->messages()->create([
            'user_id' => $user->id,
            'message' => $request->message,
            'type' => 'text'
        ]);

        // Gérer les fichiers joints
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tutoring-attachments', 'public');
                $message->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé avec succès.'
        ]);
    }

    /**
     * Generate a unique session code.
     */
    private function generateSessionCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (TutoringSession::where('session_code', $code)->exists());

        return $code;
    }

    /**
     * Generate a meeting link.
     */
    private function generateMeetingLink($sessionCode)
    {
        return "https://meet.jit.si/africode-session-{$sessionCode}";
    }

    /**
     * Notify participants about the session.
     */
    private function notifyParticipants($session, $participantIds)
    {
        // Ici vous pouvez implémenter l'envoi de notifications
        // Par exemple, envoyer des emails ou des notifications push
        foreach ($participantIds as $participantId) {
            $participant = User::find($participantId);
            // Envoyer notification...
        }
    }
}
