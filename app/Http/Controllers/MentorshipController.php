<?php

namespace App\Http\Controllers;

use App\Models\Mentorship;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutoringSession;
use App\Services\CourseMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MentorshipController extends Controller
{
    /**
     * Display a listing of mentorships for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'formateur') {
            // Vue formateur : ses mentorats en tant que mentor
            $mentorships = Mentorship::where('mentor_id', $user->id)
                ->with(['mentee', 'mentor'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('mentorship.formateur.index', compact('mentorships'));
        } else {
            // Vue apprenant : ses mentorats en tant que mentee
            $mentorships = Mentorship::where('mentee_id', $user->id)
                ->with(['mentee', 'mentor'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('mentorship.apprenant.index', compact('mentorships'));
        }
    }

    /**
     * Show the form for creating a new mentorship request.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role !== 'apprenant') {
            return redirect()->back()->with('error', 'Seuls les apprenants peuvent demander un mentorat.');
        }

        // Récupérer les formateurs disponibles comme mentors
        $mentors = User::where('role', 'formateur')
            ->where('is_active', true)
            ->with('coursesInstructed')
            ->get();

        return view('mentorship.apprenant.create', compact('mentors'));
    }

    /**
     * Store a newly created mentorship request.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mentor_id' => 'required|exists:users,id',
            'specialization_area' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Vérifier qu'il n'y a pas déjà une demande en cours
        $existingMentorship = Mentorship::where('mentor_id', $request->mentor_id)
            ->where('mentee_id', $user->id)
            ->where('specialization_area', $request->specialization_area)
            ->whereIn('status', ['pending_mentor_approval', 'active'])
            ->first();

        if ($existingMentorship) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà une demande de mentorat en cours avec ce formateur pour cette spécialisation.');
        }

        // Créer la demande de mentorat
        $mentorship = Mentorship::create([
            'mentor_id' => $request->mentor_id,
            'mentee_id' => $user->id,
            'specialization_area' => $request->specialization_area,
            'message' => $request->message,
            'status' => 'pending_mentor_approval',
            'requested_at' => now()
        ]);

        $mentor = User::find($request->mentor_id);

        // Notifier le formateur par email
        try {
            (new CourseMailService())->sendMentorshipRequestToFormateur($mentorship->load(['mentor', 'mentee']));
        } catch (\Throwable $e) {
            Log::error('Erreur email demande mentorat: ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
        }

        return redirect()->route('mentorship.index')
            ->with('success', "Votre demande de mentorat a été envoyée à {$mentor->first_name} {$mentor->last_name}.");
    }

    /**
     * Display the specified mentorship.
     */
    public function show(Mentorship $mentorship)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a accès à ce mentorat
        if ($mentorship->mentor_id !== $user->id && $mentorship->mentee_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce mentorat.');
        }

        $mentorship->load(['mentor.mentorships', 'mentor.coursesInstructed', 'mentee']);
        
        // Récupérer les sessions de tutorat liées si elles existent
        $tutoringSessions = TutoringSession::where('tutor_id', $mentorship->mentor_id)
            ->where('student_id', $mentorship->mentee_id)
            ->orderBy('start_time', 'desc')
            ->get();

        return view('mentorship.show', compact('mentorship', 'tutoringSessions'));
    }

    /**
     * Approve a mentorship request (mentor only).
     */
    public function approve(Mentorship $mentorship)
    {
        $user = Auth::user();
        
        if ($mentorship->mentor_id !== $user->id) {
            abort(403, 'Seul le mentor peut approuver cette demande.');
        }

        if ($mentorship->approve()) {
            // Notifier l'apprenant par email
            try {
                (new CourseMailService())->sendMentorshipApprovedToApprenant($mentorship->load(['mentor', 'mentee']));
            } catch (\Throwable $e) {
                Log::error('Erreur email mentorat approuvé: ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            }

            return redirect()->back()
                ->with('success', 'Demande de mentorat approuvée. L\'apprenant a été notifié par email.');
        }

        return redirect()->back()
            ->with('error', 'Impossible d\'approuver cette demande.');
    }

    /**
     * Decline a mentorship request (mentor only).
     */
    public function decline(Mentorship $mentorship)
    {
        $user = Auth::user();
        
        if ($mentorship->mentor_id !== $user->id) {
            abort(403, 'Seul le mentor peut refuser cette demande.');
        }

        if ($mentorship->decline()) {
            // Notifier l'apprenant par email
            try {
                (new CourseMailService())->sendMentorshipDeclinedToApprenant($mentorship->load(['mentor', 'mentee']));
            } catch (\Throwable $e) {
                Log::error('Erreur email mentorat refusé: ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            }

            return redirect()->back()
                ->with('success', 'Demande de mentorat refusée. L\'apprenant a été notifié par email.');
        }

        return redirect()->back()
            ->with('error', 'Impossible de refuser cette demande.');
    }

    /**
     * End a mentorship (mentor or mentee).
     */
    public function end(Mentorship $mentorship, Request $request)
    {
        $user = Auth::user();
        
        if ($mentorship->mentor_id !== $user->id && $mentorship->mentee_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce mentorat.');
        }

        $ended = false;
        if ($mentorship->mentor_id === $user->id) {
            $ended = $mentorship->endByMentor();
        } else {
            $ended = $mentorship->endByMentee();
        }

        if ($ended) {
            return redirect()->back()
                ->with('success', 'Mentorat terminé avec succès.');
        }

        return redirect()->back()
            ->with('error', 'Impossible de terminer ce mentorat.');
    }

    /**
     * Show available mentors for apprenants.
     */
    public function mentors(Request $request)
    {
        $query = User::where('role', 'formateur')
            ->where('is_active', true)
            ->with(['coursesInstructed', 'mentorships']);

        // Recherche par nom
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Filtre par spécialisation (basé sur les cours du formateur)
        if ($request->has('specialization') && !empty($request->specialization)) {
            $specialization = $request->specialization;
            $query->whereHas('coursesInstructed', function($q) use ($specialization) {
                $q->where('title', 'LIKE', "%{$specialization}%")
                  ->orWhere('short_description', 'LIKE', "%{$specialization}%")
                  ->orWhere('full_description', 'LIKE', "%{$specialization}%")
                  ->orWhereHas('category', function($categoryQuery) use ($specialization) {
                      $categoryQuery->where('name', 'LIKE', "%{$specialization}%");
                  });
            });
        }

        // Tri
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'courses':
                $query->withCount('coursesInstructed')
                      ->orderBy('courses_instructed_count', 'desc');
                break;
            case 'mentorships':
                $query->withCount(['mentorships as active_mentorships_count' => function($q) {
                    $q->where('status', 'active');
                }])
                      ->orderBy('active_mentorships_count', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('first_name', 'asc')
                      ->orderBy('last_name', 'asc');
                break;
        }

        $mentors = $query->paginate(12)->appends($request->query());

        return view('mentorship.mentors', compact('mentors'));
    }

    /**
     * Show mentorship requests for formateurs.
     */
    public function requests()
    {
        $user = Auth::user();
        
        if ($user->role !== 'formateur') {
            return redirect()->back()->with('error', 'Accès réservé aux formateurs.');
        }

        $requests = Mentorship::where('mentor_id', $user->id)
            ->where('status', 'pending_mentor_approval')
            ->with(['mentee'])
            ->orderBy('requested_at', 'desc')
            ->paginate(10);

        return view('mentorship.formateur.requests', compact('requests'));
    }
}
