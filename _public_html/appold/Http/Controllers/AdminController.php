<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Certification;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Event;
use App\Models\Task;
use App\Models\Enrollment;
use App\Models\JobApplication;
use App\Models\FormateurWithdrawal;
use App\Models\NewsletterSubscriber;
use App\Models\Lesson;
use App\Models\Module;
use App\Services\NotificationService;
use App\Services\ImageOptimizationService;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // --- Tableau de Bord ---
    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $userCounts = [
            'total' => User::count(),
            'newThisMonth' => User::whereMonth('created_at', now()->month)->count(),
        ];

        $courseCounts = [
            'total' => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft' => Course::where('status', 'draft')->count(),
            'newThisMonth' => Course::whereMonth('created_at', now()->month)->count(),
        ];

        $enrollmentCounts = [
            'total' => Enrollment::count(),
            'newThisMonth' => Enrollment::whereMonth('created_at', now()->month)->count(),
            'thisMonth' => Enrollment::whereMonth('created_at', now()->month)->count(),
        ];

        $paymentStats = [
            'total' => Payment::whereIn('status', ['completed', 'succeeded'])->sum('amount') ?? 0,
            'thisMonth' => Payment::whereIn('status', ['completed', 'succeeded'])
                ->whereMonth('created_at', now()->month)
                ->sum('amount') ?? 0,
        ];

        // Utilisateurs récents (10 derniers)
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recruitmentCounts = [
            'total' => JobApplication::count(),
            'completed' => JobApplication::where('status', 'test_completed')->count(),
        ];

        return view('admin.dashboard', compact('userCounts', 'courseCounts', 'enrollmentCounts', 'paymentStats', 'recentUsers', 'recruitmentCounts'));
    }

    // --- Gestion des Utilisateurs ---
    public function usersIndex()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users.create');
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,formateur,apprenant',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé !');
    }

    public function usersShow(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,formateur,apprenant',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour !');
    }

    public function usersDestroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé !');
    }

    public function usersExport()
    {
        // Logique d'exportation
    }

    // --- Gestion des Cours ---
    public function coursesIndex(Request $request)
    {
        $query = Course::with('formateur', 'category');
        
        // Filtres
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Tri
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $courses = $query->paginate(20);
        
        // Calculer les statistiques globales
        $stats = [
            'total' => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft' => Course::where('status', 'draft')->count(),
            'enrollments' => \App\Models\Enrollment::distinct('user_id')->count('user_id'), // Total d'apprenants uniques
        ];
        
        return view('admin.courses.index', compact('courses', 'stats'));
    }

    public function coursesCreate()
    {
        $categories = Category::all();
        $instructors = User::where('role', 'formateur')->get();
        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    public function coursesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'level' => 'required|string|in:débutant,intermédiaire,avancé,expert',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'formateur_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published',
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
            ],
            'is_certifying' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($validated['title']);
        $data['is_certifying'] = $request->has('is_certifying');

        try {
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Validation supplémentaire du type MIME
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $mimeType = $request->file('cover_image')->getMimeType();
                if (!in_array($mimeType, $allowedMimes)) {
                    return back()->withErrors(['cover_image' => 'Type de fichier non autorisé. Formats acceptés: JPEG, PNG, GIF, WebP.'])->withInput();
                }
                
                $imageService = new ImageOptimizationService();
                $data['cover_image_path'] = $imageService->optimizeCourseCover($request->file('cover_image'));
            }
            
            $course = Course::create($data);
            
            // Déclencher l'événement CourseCreated
            event(new \App\Events\CourseCreated($course));
            
            // Si le cours est publié, déclencher l'événement de publication
            if ($course->status === 'published' && !$course->published_at) {
                $course->published_at = now();
                $course->save();
                event(new \App\Events\CoursePublished($course));
            }
            
            return redirect()->route('admin.courses.index')->with('success', 'Cours créé avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création du cours: ' . $e->getMessage()])->withInput();
        }
    }

    public function coursesShow(Course $course)
    {
        $course->load([
            'formateur',
            'category',
            'modules.lessons',
            'modules.quizzes',
            'enrollments.user',
            'ratings.user'
        ]);
        
        return view('admin.courses.show', compact('course'));
    }

    public function coursesEdit(Course $course)
    {
        $categories = Category::all();
        $instructors = User::where('role', 'formateur')->get();
        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    public function coursesUpdate(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'level' => 'required|string|in:débutant,intermédiaire,avancé,expert',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'formateur_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published',
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=400,min_height=300,max_width=2000,max_height=1500'
            ],
            'is_certifying' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_certifying'] = $request->has('is_certifying');

        try {
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Delete old image if exists
                if ($course->cover_image_path) {
                    $imagePath = public_path($course->cover_image_path);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
                
                $imageService = new ImageOptimizationService();
                $data['cover_image_path'] = $imageService->optimizeCourseCover($request->file('cover_image'));
            }
            
            $wasPublished = $course->status === 'published';
            $course->update($data);
            
            // Si le cours vient d'être publié, déclencher l'événement pour la campagne email
            if (!$wasPublished && $course->status === 'published' && !$course->published_at) {
                $course->published_at = now();
                $course->save();
                event(new \App\Events\CoursePublished($course));
            } elseif (!$wasPublished && $course->status === 'published') {
                // Si déjà publié mais événement pas déclenché
                event(new \App\Events\CoursePublished($course));
            }
            
            // Create notification
            NotificationService::userAction('updated', 'course', $course->title, [
                'model_id' => $course->id,
                'action_url' => route('admin.courses.show', $course->id),
                'action_text' => 'Voir le cours',
                'icon' => 'graduation-cap',
                'color' => 'info'
            ]);
            
            return redirect()->route('admin.courses.index')->with('success', 'Cours mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour du cours: ' . $e->getMessage()])->withInput();
        }
    }

    public function coursesDestroy(Course $course)
    {
        // Delete cover image
        if ($course->cover_image_path) {
            $imagePath = public_path($course->cover_image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Cours supprimé !');
    }

    // --- Campagnes Email ---
    public function emailCampaigns()
    {
        $courses = Course::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->with('formateur')
            ->paginate(20);
        
        return view('admin.email-campaigns.index', compact('courses'));
    }

    public function sendEmailCampaign(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        if ($course->status !== 'published') {
            return back()->with('error', 'Le cours doit être publié pour envoyer une campagne.');
        }
        
        try {
            \App\Jobs\SendNewCourseEmails::dispatch($course);
            
            return back()->with('success', 'Campagne d\'email lancée avec succès ! Les emails seront envoyés via la queue.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du lancement de la campagne: ' . $e->getMessage());
        }
    }

    // --- Gestion des Certifications ---
    public function certificationsIndex()
    {
        $certifications = Certification::with(['user', 'course'])
            ->orderBy('issued_at', 'desc')
            ->paginate(20);

        // Nombre total de certificats émis sur la plateforme (un apprenant peut en avoir plusieurs)
        $totalCertificates = Certification::count();

        // Utilisateurs (apprenants) ayant obtenu au moins une certification (chaque personne ne compte qu'une fois)
        $certifiedUserIds = Certification::select('user_id')->distinct()->pluck('user_id');
        $certifiedApprenants = User::whereIn('id', $certifiedUserIds)
            ->withCount('certifications')
            ->with(['certifications' => fn ($q) => $q->orderBy('issued_at', 'desc')->with('course')])
            ->orderBy('certifications_count', 'desc')
            ->get();
        $totalCertifiedApprenants = $certifiedApprenants->count();

        return view('admin.certifications.index', compact('certifications', 'certifiedApprenants', 'totalCertifiedApprenants', 'totalCertificates'));
    }

    public function certificationsShow(Certification $certification)
    {
        $certification->load(['user', 'course']);
        return view('admin.certifications.show', compact('certification'));
    }

    /**
     * Page admin Tracking / Analytics : état de la config et liens vers les tableaux de bord (GA4, GTM, Meta).
     */
    public function trackingIndex(): View
    {
        $config = config('tracking', []);
        $enabled = $config['enabled'] ?? false;
        $gtmId = $config['gtm_id'] ?? null;
        $meta = $config['meta'] ?? [];
        $ga4 = $config['ga4'] ?? [];
        $linkedin = $config['linkedin'] ?? [];
        $tiktok = $config['tiktok'] ?? [];
        $twitter = $config['twitter'] ?? [];

        $metaEnabled = !empty($meta['enabled']) && !empty($meta['pixel_id']);
        $ga4Enabled = !empty($ga4['enabled']) && !empty($ga4['measurement_id']);
        $linkedinEnabled = !empty($linkedin['enabled']) && !empty($linkedin['partner_id']);
        $tiktokEnabled = !empty($tiktok['enabled']) && !empty($tiktok['pixel_id']);
        $twitterEnabled = !empty($twitter['enabled']) && !empty($twitter['pixel_id']);

        return view('admin.tracking.index', compact(
            'enabled', 'gtmId', 'meta', 'ga4', 'linkedin', 'tiktok', 'twitter',
            'metaEnabled', 'ga4Enabled', 'linkedinEnabled', 'tiktokEnabled', 'twitterEnabled'
        ));
    }

    // --- Statistiques ---
    public function statisticsIndex()
    {
        // Calculer les inscriptions mensuelles (12 derniers mois)
        $monthlyEnrollments = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Enrollment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyEnrollments[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        $paymentSuccessStatuses = ['succeeded', 'completed'];

        // Calculer les revenus mensuels (12 derniers mois)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $amount = Payment::whereIn('status', $paymentSuccessStatuses)
                ->where('payable_type', Course::class)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount') ?? 0;
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'amount' => $amount
            ];
        }

        // Calculer les niveaux de cours (valeurs possibles: debutant/beginner, intermediaire/intermediate, avance/advanced)
        $courseLevels = [
            'Débutant' => Course::whereIn('level', ['debutant', 'beginner'])->where('status', 'published')->count(),
            'Intermédiaire' => Course::whereIn('level', ['intermediaire', 'intermediate'])->where('status', 'published')->count(),
            'Avancé' => Course::whereIn('level', ['avance', 'advanced'])->where('status', 'published')->count(),
        ];

        // Calculer les rôles d'utilisateurs (admin + administrateur)
        $userRoles = [
            'Administrateurs' => User::whereIn('role', ['admin', 'administrateur'])->count(),
            'Formateurs' => User::where('role', 'formateur')->count(),
            'Apprenants' => User::where('role', 'apprenant')->orWhereNull('role')->count(),
        ];

        // Top 10 cours les plus populaires (par nombre d'inscriptions)
        $topCourses = Course::with(['formateur', 'category'])
            ->withCount('enrollments')
            ->where('status', 'published')
            ->orderBy('enrollments_count', 'desc')
            ->limit(10)
            ->get();

        // Top 10 formateurs les plus actifs (par inscriptions)
        $topFormateurs = User::where('role', 'formateur')
            ->with('coursesInstructed')
            ->get()
            ->map(function ($formateur) use ($paymentSuccessStatuses) {
                $courseIds = $formateur->coursesInstructed->pluck('id');
                $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();
                
                // Calculer les revenus du formateur (77% des paiements réussis)
                $totalRevenue = Payment::whereIn('status', $paymentSuccessStatuses)
                    ->where('payable_type', Course::class)
                    ->whereIn('payable_id', $courseIds)
                    ->sum('amount') ?? 0;
                $formateurRevenue = $totalRevenue * 0.77;
                
                $formateur->total_enrollments = $totalEnrollments;
                $formateur->total_revenue = $formateurRevenue;
                return $formateur;
            })
            ->sortByDesc('total_enrollments')
            ->take(10)
            ->values();

        // --- Comptabilité : données pour la section dédiée ---
        $totalCoursVendusMontant = Payment::whereIn('status', $paymentSuccessStatuses)
            ->where('payable_type', Course::class)
            ->sum('amount');
        $comptabilite = [
            'montant_total_cours_publies' => Course::where('status', 'published')->sum('price'),
            'montant_cours_vendus' => $totalCoursVendusMontant,
            'montant_retraits_total' => FormateurWithdrawal::where('status', 'completed')->sum('amount'),
            'revenus_africode' => $totalCoursVendusMontant * 0.23, // 23 % pour AfriCode (77 % formateur)
            'total_personnes' => JobApplication::count()
                + (int) User::where('role', 'apprenant')->orWhereNull('role')->count()
                + (int) User::where('role', 'formateur')->count()
                + NewsletterSubscriber::count(),
        ];

        // Formateurs classés par ventes (revenus du plus grand au plus petit)
        // Uniquement les formateurs ayant vendu au moins 2 cours (au moins 2 ventes / paiements réussis)
        $formateursParVentes = User::where('role', 'formateur')
            ->with('coursesInstructed')
            ->get()
            ->map(function ($formateur) use ($paymentSuccessStatuses) {
                $courseIds = $formateur->coursesInstructed->pluck('id');
                if ($courseIds->isEmpty()) {
                    $formateur->total_enrollments = 0;
                    $formateur->total_revenue = 0;
                    $formateur->total_ventes_brut = 0;
                    $formateur->nombre_ventes = 0;
                    return $formateur;
                }
                $payments = Payment::whereIn('status', $paymentSuccessStatuses)
                    ->where('payable_type', Course::class)
                    ->whereIn('payable_id', $courseIds)
                    ->get();
                $totalRevenue = $payments->sum('amount');
                $nombreVentes = $payments->count();
                $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();

                $formateur->total_enrollments = $totalEnrollments;
                $formateur->total_revenue = round($totalRevenue * 0.77, 2);
                $formateur->total_ventes_brut = $totalRevenue;
                $formateur->nombre_ventes = $nombreVentes;
                return $formateur;
            })
            ->filter(function ($formateur) {
                return ($formateur->nombre_ventes ?? 0) >= 2;
            })
            ->sortByDesc('total_ventes_brut')
            ->values();

        // Statistiques par catégorie
        $categoriesStats = Category::withCount('courses')
            ->whereHas('courses', function($q) {
                $q->where('status', 'published');
            })
            ->orderBy('courses_count', 'desc')
            ->get();

        return view('admin.statistics.index', compact(
            'monthlyEnrollments',
            'monthlyRevenue',
            'courseLevels',
            'userRoles',
            'topCourses',
            'topFormateurs',
            'categoriesStats',
            'comptabilite',
            'formateursParVentes'
        ));
    }

    // --- Paiements ---
    public function paymentsIndex(Request $request)
    {
        // Paiements réussis : "succeeded" (Stripe, etc.) ou "completed" (FadaPay, PayPal, etc.)
        $successStatuses = ['succeeded', 'completed'];

        $query = Payment::with(['user', 'payable', 'enrollment'])
            ->whereIn('status', $successStatuses)
            ->where('payable_type', Course::class)
            ->whereHas('payable', function ($q) {
                $q->where('price', '>', 0);
            })
            ->orderBy('created_at', 'desc');

        // Recherche optionnelle
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('payable', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->paginate(20);

        $payments->load([
            'user',
            'payable' => function ($query) {
                $query->with('formateur');
            }
        ]);

        // Statistiques : mêmes critères (succeeded + completed, cours payants)
        $baseQuery = Payment::whereIn('status', $successStatuses)
            ->where('payable_type', Course::class)
            ->whereHas('payable', function ($q) {
                $q->where('price', '>', 0);
            });

        $totalAmount = $baseQuery->sum('amount') ?? 0;
        $thisMonthAmount = (clone $baseQuery)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') ?? 0;
        $totalCount = $baseQuery->count();

        $stats = [
            'total' => $totalCount,
            'succeeded' => $totalCount,
            'total_amount' => $totalAmount,
            'this_month_amount' => $thisMonthAmount,
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function paymentsShow(Payment $payment)
    {
        $payment->load(['user', 'payable', 'enrollment']);
        // Charger le formateur si payable est un cours
        if ($payment->payable instanceof Course) {
            $payment->payable->load('formateur');
        }
        return view('admin.payments.show', compact('payment'));
    }

    public function paymentsExport()
    {
        // Logique d'exportation
    }

    // --- Messages ---
    public function messagesIndex()
    {
        return view('admin.messages.index');
    }

    public function messagesShow(Message $message)
    {
        return view('admin.messages.show', compact('message'));
    }

    public function messagesDestroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message supprimé !');
    }

    // --- Événements ---
    public function eventsIndex()
    {
        $events = Event::orderBy('start_datetime', 'desc')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function eventsCreate()
    {
        return view('admin.events.create');
    }

    public function eventsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'description' => 'nullable|string',
        ]);
        Event::create($validated);
        return redirect()->route('admin.events.index')->with('success', 'Événement créé.');
    }

    public function eventsShow(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function eventsEdit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function eventsUpdate(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'description' => 'nullable|string',
        ]);
        $event->update($validated);
        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour.');
    }

    public function eventsDestroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Événement supprimé.');
    }

    // --- Tâches ---
    public function tasksIndex()
    {
        $tasks = Task::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.tasks.index', compact('tasks'));
    }

    public function tasksCreate()
    {
        return view('admin.tasks.create');
    }

    public function tasksStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);
        $validated['assigned_to_id'] = $request->input('assigned_to_id') ?: Auth::id();
        Task::create($validated);
        return redirect()->route('admin.tasks.index')->with('success', 'Tâche créée.');
    }

    public function tasksEdit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    public function tasksUpdate(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);
        $task->update($validated);
        return redirect()->route('admin.tasks.index')->with('success', 'Tâche mise à jour.');
    }

    public function tasksDestroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Tâche supprimée.');
    }

    public function tasksMarkComplete(Task $task)
    {
        $task->update(['is_completed' => true]);
        return back()->with('success', 'Tâche marquée comme terminée.');
    }

    // --- Notifications ---
    public function notificationsIndex()
    {
        $notifications = \App\Models\Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function notificationsMarkAllRead()
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true, 'read_at' => now()]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function notificationsMarkRead(\App\Models\Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->update(['read' => true, 'read_at' => now()]);
        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function notificationsDestroy(\App\Models\Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->delete();
        return back()->with('success', 'Notification supprimée.');
    }

    // --- Paramètres ---
    public function settingsEdit()
    {
        $settings = [
            'site_name' => config('app.name', 'AfriCode'),
            'currency' => config('app.currency', 'XOF'),
            'site_description' => config('app.description', ''),
            'registration_enabled' => config('app.registration_enabled', true),
            'allow_instructor_signup' => config('app.allow_instructor_signup', true),
            'contact_email' => config('mail.from.address', env('MAIL_FROM_ADDRESS', '')),
            'support_email' => config('mail.support_email', config('mail.from.address', '')),
            'address' => config('app.address', ''),
            'phone' => config('app.phone', ''),
            'business_hours' => config('app.business_hours', ''),
            'facebook_url' => config('app.social.facebook', ''),
            'twitter_url' => config('app.social.twitter', ''),
            'instagram_url' => config('app.social.instagram', ''),
            'linkedin_url' => config('app.social.linkedin', ''),
            'youtube_url' => config('app.social.youtube', ''),
            'payment_gateway' => config('app.payment_gateway', 'stripe'),
            'stripe_key' => config('services.stripe.key', ''),
            'stripe_secret' => config('services.stripe.secret', ''),
            'orange_money_merchant_id' => config('services.orange_money.merchant_id', ''),
            'orange_money_api_key' => config('services.orange_money.api_key', ''),
            'platform_commission' => config('app.platform_commission', 20),
            'maintenance_mode' => config('app.maintenance_mode', false),
            'max_file_upload_size' => (int) (config('app.max_file_upload_size', 10)),
        ];
        return view('admin.settings.edit', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        // Validation
        // Sauvegarde des paramètres
        return redirect()->route('admin.settings.edit')->with('success', 'Paramètres mis à jour !');
    }

    // --- Gestion des Leçons ---
    
    public function lessonsCreate(Request $request)
    {
        $moduleId = $request->get('module_id');
        $module = Module::with('course')->findOrFail($moduleId);
        return view('admin.lessons.create', compact('module'));
    }

    public function lessonsStore(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,pdf',
            'order' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'video_url' => 'nullable|required_if:type,video|url',
            'text_content' => 'nullable|required_if:type,text|string',
            'pdf_file' => 'nullable|required_if:type,pdf|file|mimes:pdf|max:10240',
        ]);
        
        try {
            $lesson = new Lesson();
            $lesson->module_id = $validated['module_id'];
            $lesson->title = $validated['title'];
            $lesson->description = $validated['description'] ?? '';
            $lesson->type = $validated['type'];
            $lesson->order = $validated['order'] ?? Lesson::where('module_id', $validated['module_id'])->max('order') + 1;
            $lesson->duration = $validated['duration'] ?? null;
            
            // Handle content based on type
            if ($validated['type'] === 'video') {
                $lesson->content = $validated['video_url'];
            } elseif ($validated['type'] === 'text') {
                $lesson->content = $validated['text_content'];
            } elseif ($validated['type'] === 'pdf' && $request->hasFile('pdf_file')) {
                $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                $lesson->content = $path;
            }
            
            $lesson->save();
            
            $module = Module::findOrFail($validated['module_id']);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon ajoutée avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de la leçon: ' . $e->getMessage()])->withInput();
        }
    }

    public function lessonsShow(Lesson $lesson)
    {
        $lesson->load(['module.course']);
        return view('admin.lessons.show', compact('lesson'));
    }

    public function lessonsEdit(Lesson $lesson)
    {
        $module = $lesson->module;
        $course = $module->course;
        return view('admin.lessons.edit', compact('lesson', 'module', 'course'));
    }

    public function lessonsUpdate(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,pdf',
            'order' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'video_url' => 'nullable|required_if:type,video|url',
            'text_content' => 'nullable|required_if:type,text|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        
        try {
            $lesson->title = $validated['title'];
            $lesson->description = $validated['description'] ?? '';
            $lesson->type = $validated['type'];
            $lesson->order = $validated['order'] ?? $lesson->order;
            $lesson->duration = $validated['duration'] ?? $lesson->duration;
            
            // Handle content based on type
            if ($validated['type'] === 'video') {
                $lesson->content = $validated['video_url'];
            } elseif ($validated['type'] === 'text') {
                $lesson->content = $validated['text_content'];
            } elseif ($validated['type'] === 'pdf' && $request->hasFile('pdf_file')) {
                // Delete old file if exists and is a PDF file
                if ($lesson->type === 'pdf' && Storage::disk('public')->exists($lesson->content)) {
                    Storage::disk('public')->delete($lesson->content);
                }
                $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                $lesson->content = $path;
            }
            
            $lesson->save();
            
            $module = $lesson->module;
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon mise à jour avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour de la leçon: ' . $e->getMessage()])->withInput();
        }
    }

    public function lessonsDestroy(Lesson $lesson)
    {
        try {
            $module = $lesson->module;
            $courseId = $module->course_id;
            $lesson->delete();
            
            return redirect()->route('admin.courses.show', $courseId)
                ->with('success', 'Leçon supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression de la leçon: ' . $e->getMessage()]);
        }
    }
}
