<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Course;
use App\Models\Certification;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Event;
use App\Models\Task;
use App\Models\Notification;
use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Module;
use App\Models\Lesson;
use App\Services\NotificationService;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // Méthode constructeur pour appliquer le middleware (alternative au groupe de routes)
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'isAdmin']);
    // }

    // --- Tableau de Bord ---
    public function dashboard()
    {
        // User statistics
        $userCounts = [
            'total' => User::count(),
            'admins' => User::where('role', 'administrateur')->count(),
            'formateurs' => User::where('role', 'formateur')->count(),
            'apprenants' => User::where('role', 'apprenant')->count(),
            'newThisMonth' => User::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Course statistics
        $courseCounts = [
            'total' => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft' => Course::where('status', 'draft')->count(),
            'newThisMonth' => Course::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Enrollment statistics
        $enrollmentCounts = [
            'total' => \App\Models\Enrollment::count(),
            'today' => \App\Models\Enrollment::whereDate('created_at', today())->count(),
            'thisWeek' => \App\Models\Enrollment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'thisMonth' => \App\Models\Enrollment::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Payment statistics (if applicable)
        $paymentStats = [
            'total' => \App\Models\Payment::sum('amount'),
            'thisMonth' => \App\Models\Payment::whereMonth('created_at', now()->month)->sum('amount'),
            'count' => \App\Models\Payment::count(),
        ];
        
        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        
        // Recent courses
        $recentCourses = Course::with('formateur')->orderBy('created_at', 'desc')->take(5)->get();
        
        // Recent enrollments
        $recentEnrollments = \App\Models\Enrollment::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Recent activities (could be from any activity log if you have one)
        
        // Pending tasks
        $pendingTasks = Task::where('is_completed', false)
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'userCounts', 
            'courseCounts', 
            'enrollmentCounts',
            'paymentStats',
            'recentUsers',
            'recentCourses',
            'recentEnrollments',
            'pendingTasks'
        ));
    }

    // --- Gestion des Utilisateurs ---
    public function usersIndex()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrateur,formateur,apprenant',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['email_verified_at'] = now(); // Auto-verify admin-created users

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('profile_images', $filename, 'public');
            $validated['profile_image_path'] = $path;
        }

        $user = User::create($validated);
        
        // Create notification
        NotificationService::userAction('created', 'user', $user->first_name . ' ' . $user->last_name, [
            'model_id' => $user->id,
            'action_url' => route('admin.users.show', $user->id),
            'action_text' => 'Voir l\'utilisateur'
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès !');
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:administrateur,formateur,apprenant',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Handle password update only if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }
        
        $validated['is_active'] = $request->has('is_active');

        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image_path) {
                Storage::disk('public')->delete($user->profile_image_path);
            }
            
            $image = $request->file('profile_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('profile_images', $filename, 'public');
            $validated['profile_image_path'] = $path;
        }

        $user->update($validated);
        
        // Create notification
        NotificationService::userAction('updated', 'user', $user->first_name . ' ' . $user->last_name, [
            'model_id' => $user->id,
            'action_url' => route('admin.users.show', $user->id),
            'action_text' => 'Voir l\'utilisateur'
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    public function usersDestroy(User $user)
    {
        // Prevent administrators from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Check if user has courses and handle them
        if ($user->role === 'formateur' && $user->coursesInstructed()->exists()) {
            // Option 1: Prevent deletion
            return redirect()->route('admin.users.index')->with('error', 'Cet utilisateur a des cours associés. Veuillez d\'abord réassigner ou supprimer ces cours.');
            
            /* Option 2: Cascade delete courses
            foreach ($user->coursesInstructed as $course) {
                $course->delete();
            }
            */
        }
        
        // Use soft delete (if configured in User model)
        $userName = $user->first_name . ' ' . $user->last_name;
        $user->delete();
        
        // Create notification
        NotificationService::userAction('deleted', 'user', $userName, [
            'color' => 'danger',
            'icon' => 'user-slash'
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès !');
    }

    public function usersExport()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email', 'role', 'is_active', 'created_at', 'updated_at')->get();
        
        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-export-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add CSV header
            fputcsv($file, ['ID', 'Prénom', 'Nom', 'Email', 'Rôle', 'Actif', 'Créé le', 'Mis à jour le']);
            
            // Add users
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->role,
                    $user->is_active ? 'Oui' : 'Non',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }


    // --- Gestion des Cours ---
    public function coursesIndex()
    {
        $courses = Course::with('formateur')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.courses.index', compact('courses'));
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
            'description' => 'required|string',
            'slug' => 'required|string|unique:courses,slug',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:debutant,intermediaire,avance,expert',
            'category_id' => 'required|exists:categories,id',
            'formateur_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:2048', // 2MB max
            'preview_video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:0',
        ]);
        
        try {
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('course_covers', $filename, 'public');
                $validated['cover_image_path'] = $path;
            }
            
            $course = Course::create($validated);
            
            // Create notification
            NotificationService::userAction('created', 'course', $course->title, [
                'model_id' => $course->id,
                'action_url' => route('admin.courses.show', $course->id),
                'action_text' => 'Voir le cours',
                'icon' => 'graduation-cap',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.index')->with('success', 'Cours créé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'course', $validated['title']);
        }
    }

    public function coursesShow(Course $course)
    {
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
            'description' => 'required|string',
            'slug' => 'required|string|unique:courses,slug,' . $course->id,
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:debutant,intermediaire,avance,expert',
            'category_id' => 'required|exists:categories,id',
            'formateur_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:2048', // 2MB max
            'preview_video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:0',
        ]);
        
        try {
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Delete old image if exists
                if ($course->cover_image_path) {
                    Storage::disk('public')->delete($course->cover_image_path);
                }
                
                $image = $request->file('cover_image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('course_covers', $filename, 'public');
                $validated['cover_image_path'] = $path;
            }
            
            $course->update($validated);
            
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
            return $this->handleOperationError($e, 'updated', 'course', $course->title);
        }
    }

    public function coursesDestroy(Course $course)
    {
        // Check for enrollments
        if ($course->enrollments()->exists()) {
            return redirect()->route('admin.courses.index')->with('error', 
                'Ce cours a des inscriptions actives. Vous ne pouvez pas le supprimer.');
        }
        
        try {
            $courseTitle = $course->title;
            
            // Delete all related modules, lessons, and quizzes
            foreach ($course->modules as $module) {
                // Delete lessons and quizzes within each module
                $module->lessons()->delete();
                $module->quizzes()->delete();
                $module->delete();
            }
            
            // Delete cover image
            if ($course->cover_image_path) {
                Storage::disk('public')->delete($course->cover_image_path);
            }
            
            // Delete course
            $course->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'course', $courseTitle, [
                'icon' => 'graduation-cap',
                'color' => 'danger'
            ]);
            
            return redirect()->route('admin.courses.index')
                ->with('success', 'Cours et tout son contenu supprimés avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'course', $course->title);
        }
    }


    // --- Gestion des Certifications ---
    public function certificationsIndex()
    {
        // Lister les certifications
        return view('admin.certifications.index');
    }

     public function certificationsShow(Certification $certification)
    {
        // Afficher une certification
        return view('admin.certifications.show', compact('certification'));
    }

    // public function certificationsGenerate(Request $request) { /* Logique de génération */ }


    // --- Statistiques ---
    public function statisticsIndex()
    {
        // Date range for monthly stats
        $months = collect(range(0, 11))->map(function($month) {
            $date = now()->subMonths($month);
            return [
                'month' => $date->format('M Y'),
                'start' => $date->startOfMonth()->format('Y-m-d'),
                'end' => $date->endOfMonth()->format('Y-m-d'),
            ];
        })->reverse();
        
        // Monthly user registrations
        $monthlyUsers = [];
        foreach ($months as $monthData) {
            $monthlyUsers[] = [
                'month' => $monthData['month'],
                'count' => User::whereBetween('created_at', [$monthData['start'], $monthData['end']])->count()
            ];
        }
        
        // Monthly enrollments
        $monthlyEnrollments = [];
        foreach ($months as $monthData) {
            $monthlyEnrollments[] = [
                'month' => $monthData['month'],
                'count' => \App\Models\Enrollment::whereBetween('created_at', [$monthData['start'], $monthData['end']])->count()
            ];
        }
        
        // Monthly revenue
        $monthlyRevenue = [];
        foreach ($months as $monthData) {
            $monthlyRevenue[] = [
                'month' => $monthData['month'],
                'amount' => \App\Models\Payment::whereBetween('created_at', [$monthData['start'], $monthData['end']])
                    ->sum('amount')
            ];
        }
        
        // Course statistics by category
        $categoriesStats = \App\Models\Category::withCount('courses')->get();
        
        // User role breakdown
        $userRoles = [
            'Administrateurs' => User::where('role', 'administrateur')->count(),
            'Formateurs' => User::where('role', 'formateur')->count(),
            'Apprenants' => User::where('role', 'apprenant')->count(),
        ];
        
        // Course level breakdown
        $courseLevels = [
            'Débutant' => Course::where('level', 'beginner')->count(),
            'Intermédiaire' => Course::where('level', 'intermediate')->count(),
            'Avancé' => Course::where('level', 'advanced')->count(),
        ];
        
        // Top courses by enrollment
        $topCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();
            
        // Top formateurs by enrollment
        $topFormateurs = User::where('role', 'formateur')
            ->withCount(['coursesInstructed as total_enrollments' => function($query) {
                $query->join('enrollments', 'courses.id', '=', 'enrollments.course_id');
            }])
            ->orderBy('total_enrollments', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.statistics.index', compact(
            'monthlyUsers',
            'monthlyEnrollments',
            'monthlyRevenue',
            'categoriesStats',
            'userRoles',
            'courseLevels',
            'topCourses',
            'topFormateurs'
        ));
    }


    // --- Paiements ---
    public function paymentsIndex()
    {
        // Lister les paiements
        return view('admin.payments.index');
    }

    public function paymentsShow(Payment $payment)
    {
        // Afficher détails paiement
        return view('admin.payments.show', compact('payment'));
    }

    public function paymentsExport()
    {
        // Logique d'exportation
    }


    // --- Messages ---
    public function messagesIndex()
    {
        // Lister les messages
        return view('admin.messages.index');
    }

    public function messagesShow(Message $message)
    {
        // Afficher un message
        return view('admin.messages.show', compact('message'));
    }

    public function messagesDestroy(Message $message)
    {
        // Supprimer message
        // Redirection
    }

    // public function messagesReply(Request $request, Message $message) { /* Logique de réponse */ }


    // --- Événements (Calendrier) ---
    public function eventsIndex()
    {
        // Récupérer événements pour le calendrier (souvent via une requête API pour JS)
        // Peut retourner du JSON ou une vue de base
        // $events = Event::all();
        // return response()->json($events); ou return view('admin.events.index', compact('events'));
         return view('admin.events.index'); // Ou une vue pour le calendrier complet
    }

    public function eventsCreate() { return view('admin.events.create'); }
    public function eventsStore(Request $request) { /* Validation, Création, Redirection */ }
    public function eventsShow(Event $event) { /* Retourner détails pour modal ? */ }
    public function eventsEdit(Event $event) { return view('admin.events.edit', compact('event')); }
    public function eventsUpdate(Request $request, Event $event) { /* Validation, Mise à jour, Redirection */ }
    public function eventsDestroy(Event $event) { /* Suppression, Redirection ou JSON */ }


    // --- Tâches ---
    public function tasksIndex() { /* Lister tâches ? Ou géré dans le dashboard */ }
    public function tasksCreate() { return view('admin.tasks.create'); } // Probablement une modale
    public function tasksStore(Request $request) { /* Validation, Création */ }
    public function tasksEdit(Task $task) { return view('admin.tasks.edit', compact('task')); } // Probablement une modale
    public function tasksUpdate(Request $request, Task $task) { /* Validation, Mise à jour */ }
    public function tasksDestroy(Task $task) { /* Suppression */ }
    public function tasksMarkComplete(Task $task)
    {
        // $task->update(['is_completed' => true]); // Ou statut différent
        // Retourner réponse JSON ou rediriger
    }


    // --- Notifications ---
    /**
     * Display a listing of notifications
     */
    public function notificationsIndex()
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function notificationsMarkRead(Notification $notification)
    {
        // Check if notification belongs to current user
        if ($notification->user_id !== Auth::id()) {
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette notification.');
        }
        
        $notification->markAsRead();
        
        // If there's an action URL, redirect to it
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Mark all notifications as read
     */
    public function notificationsMarkAllRead()
    {
        $count = NotificationService::markAllAsRead();
        
        return redirect()->route('admin.notifications.index')
            ->with('success', "$count notifications marquées comme lues.");
    }

    /**
     * Delete a notification
     */
    public function notificationsDestroy(Notification $notification)
    {
        // Check if notification belongs to current user
        if ($notification->user_id !== Auth::id()) {
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette notification.');
        }
        
        $notification->delete();
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification supprimée avec succès.');
    }


    // --- Paramètres ---
    public function settingsEdit()
    {
        // We'll use the file-based settings approach 
        // You can store settings in a database table if you prefer
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => config('app.description', 'Plateforme d\'apprentissage en ligne pour l\'Afrique'),
            'contact_email' => config('app.contact_email', 'contact@africode.com'),
            'support_email' => config('app.support_email', 'support@africode.com'),
            'facebook_url' => config('app.social.facebook', ''),
            'twitter_url' => config('app.social.twitter', ''),
            'instagram_url' => config('app.social.instagram', ''),
            'linkedin_url' => config('app.social.linkedin', ''),
            'youtube_url' => config('app.social.youtube', ''),
            'maintenance_mode' => config('app.maintenance_mode', false),
            'registration_enabled' => config('app.registration_enabled', true),
            'payment_gateway' => config('app.payment_gateway', 'stripe'),
            'currency' => config('app.currency', 'XOF'),
            'max_file_upload_size' => config('app.max_file_upload_size', 10),
            'allow_instructor_signup' => config('app.allow_instructor_signup', true),
        ];
        
        return view('admin.settings.edit', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'contact_email' => 'required|email',
            'support_email' => 'required|email',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'maintenance_mode' => 'boolean',
            'registration_enabled' => 'boolean',
            'payment_gateway' => 'required|in:stripe,paypal,orange_money,wave,free',
            'currency' => 'required|string|size:3',
            'max_file_upload_size' => 'required|integer|min:1|max:100',
            'allow_instructor_signup' => 'boolean',
        ]);
        
        // For file based settings we'd need to update the .env file
        // This is just an example and would require a package like:
        // https://github.com/LaravelDaily/laravel-settings
        
        // Update .env file (simplified example)
        $envFile = base_path('.env');
        
        if (file_exists($envFile)) {
            // Site name
            file_put_contents($envFile, preg_replace(
                '/APP_NAME=.*/',
                'APP_NAME="' . $validated['site_name'] . '"',
                file_get_contents($envFile)
            ));
            
            // For a real implementation, consider using a package or 
            // creating a Settings model to store in database
        }
        
        // Alternatively, for database settings:
        // foreach ($validated as $key => $value) {
        //     Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        // }
        
        // Clear configuration cache
        \Artisan::call('config:clear');
        
        return redirect()->route('admin.settings.edit')
            ->with('success', 'Paramètres mis à jour avec succès !');
    }
    
    /**
     * Handle admin operation errors and create notifications
     *
     * @param Exception $exception The exception to handle
     * @param string $action The action that was being performed
     * @param string $modelType The type of model affected
     * @param string $modelName The name or identifier of the model
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleOperationError(\Exception $exception, string $action, string $modelType, string $modelName)
    {
        // Log the error
        \Log::error("Admin operation error: {$action} {$modelType} '{$modelName}' failed", [
            'exception' => $exception,
            'user_id' => Auth::id(),
            'model_type' => $modelType,
            'model_name' => $modelName
        ]);
        
        // Create error notification for admins
        NotificationService::errorNotification(
            "Erreur lors de l'opération {$action}",
            "Une erreur est survenue lors de l'opération {$action} sur {$modelType} \"{$modelName}\": " . $exception->getMessage(),
            [
                'action_url' => route('admin.dashboard')
            ]
        );
        
        // Redirect with error message
        $modelLabel = match($modelType) {
            'user' => "l'utilisateur",
            'course' => "le cours",
            'module' => "le module",
            'lesson' => "la leçon",
            default => $modelType
        };
        
        $actionVerb = match($action) {
            'created' => "la création de",
            'updated' => "la mise à jour de",
            'deleted' => "la suppression de",
            default => $action
        };
        
        return redirect()->back()
            ->withInput()
            ->with('error', "Une erreur est survenue lors de {$actionVerb} {$modelLabel}. Veuillez réessayer.");
    }
    
    /**
     * Show the form for editing a quiz question.
     * 
     * @param  \App\Models\Question  $question
     * @return \Illuminate\View\View
     */
    public function questionEdit(Question $question)
    {
        // Get related quiz
        $quiz = $question->quiz;
        
        // Determine the parent module/course
        $module = null;
        $course = null;
        
        if ($quiz->related_type === 'App\Models\Module' || $quiz->related_type === 'Module') {
            $module = \App\Models\Module::find($quiz->related_id);
            if ($module) {
                $course = $module->course;
            }
        }
        
        // Check if answers exist for this question
        $answers = $question->answers;
        
        return view('admin.quizzes.questions.edit', compact('question', 'quiz', 'module', 'course', 'answers'));
    }

    /**
     * Update a quiz question.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function questionUpdate(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'required_if:type,multiple_choice|array',
            'correct_answer' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'explanation' => 'nullable|string',
        ]);
        
        try {
            $question->text = $validated['question']; // Map 'question' field to 'text' column
            $question->type = $validated['type'];
            
            if ($validated['type'] === 'multiple_choice') {
                $question->options = json_encode($validated['options']);
            } elseif ($validated['type'] === 'true_false') {
                $question->options = json_encode(['true', 'false']);
            } else {
                $question->options = null;
            }
            
            $question->correct_answer = $validated['correct_answer'];
            $question->points = $validated['points'] ?? 1;
            $question->explanation = $validated['explanation'] ?? null;
            $question->save();
            
            // Get quiz for redirect
            $quiz = $question->quiz;
            
            // Create notification
            NotificationService::userAction('updated', 'question', substr($question->text, 0, 30) . '...', [
                'model_id' => $question->id,
                'action_url' => route('admin.quizzes.edit', $quiz->id),
                'action_text' => 'Voir le quiz',
                'icon' => 'question',
                'color' => 'info'
            ]);
            
            return redirect()->route('admin.quizzes.edit', $quiz->id)
                ->with('success', 'Question mise à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'question', substr($validated['text'], 0, 30) . '...');
        }
    }
    
    /**
     * Remove a quiz question.
     * 
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function questionDestroy(Question $question)
    {
        try {
            $quiz = $question->quiz;
            $title = substr($question->text, 0, 30) . '...';
            
            // Delete the question
            $question->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'question', $title, [
                'action_url' => route('admin.quizzes.edit', $quiz->id),
                'action_text' => 'Voir le quiz',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route('admin.quizzes.edit', $quiz->id)
                ->with('success', 'Question supprimée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'question', substr($question->text, 0, 30) . '...');
        }
    }
    
    /**
     * Store a newly created quiz.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function quizzesStore(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'related_type' => 'required|string',
            'related_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
        ]);
        
        try {
            $quiz = new Quiz();
            $quiz->related_type = $validated['related_type'];
            $quiz->related_id = $validated['related_id'];
            $quiz->title = $validated['title'];
            $quiz->description = $validated['description'] ?? '';
            $quiz->passing_score = $validated['passing_score'] ?? 70;
            $quiz->time_limit = $validated['time_limit'] ?? null;
            $quiz->is_required = $request->has('is_required');
            $quiz->save();
            
            // Get module for redirect
            $module = \App\Models\Module::findOrFail($validated['module_id']);
            
            // Create notification
            NotificationService::userAction('created', 'quiz', $quiz->title, [
                'model_id' => $quiz->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'question-circle',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Quiz créé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'quiz', $validated['title']);
        }
    }
    
    /**
     * Show the form for editing the specified quiz.
     * 
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\View\View
     */
    public function quizzesEdit(Quiz $quiz)
    {
        $module = null;
        
        // Determine the parent module/course
        if ($quiz->related_type === 'App\Models\Module') {
            $module = \App\Models\Module::find($quiz->related_id);
            $course = $module ? $module->course : null;
        }
        
        return view('admin.quizzes.edit', compact('quiz', 'module', 'course'));
    }
    
    /**
     * Update the specified quiz.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\RedirectResponse
     */
    public function quizzesUpdate(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
        ]);
        
        try {
            $quiz->title = $validated['title'];
            $quiz->description = $validated['description'] ?? '';
            $quiz->passing_score = $validated['passing_score'] ?? 70;
            $quiz->time_limit = $validated['time_limit'] ?? null;
            $quiz->is_required = $request->has('is_required');
            $quiz->save();
            
            // Get related module for redirect
            $module = null;
            if ($quiz->related_type === 'App\Models\Module') {
                $module = \App\Models\Module::find($quiz->related_id);
            }
            
            // Create notification
            NotificationService::userAction('updated', 'quiz', $quiz->title, [
                'model_id' => $quiz->id,
                'action_url' => $module ? route('admin.courses.show', $module->course_id) : route('admin.dashboard'),
                'action_text' => $module ? 'Voir le cours' : 'Dashboard',
                'icon' => 'question-circle',
                'color' => 'info'
            ]);
            
            return redirect()->route($module ? 'admin.courses.show' : 'admin.dashboard', $module ? $module->course_id : null)
                ->with('success', 'Quiz mis à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'quiz', $quiz->title);
        }
    }
    
    /**
     * Remove the specified quiz.
     * 
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\RedirectResponse
     */
    public function quizzesDestroy(Quiz $quiz)
    {
        try {
            $title = $quiz->title;
            
            // Get related module for redirect
            $module = null;
            if ($quiz->related_type === 'App\Models\Module') {
                $module = \App\Models\Module::find($quiz->related_id);
            }
            
            // Delete the quiz and its questions
            $quiz->questions()->delete();
            $quiz->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'quiz', $title, [
                'action_url' => $module ? route('admin.courses.show', $module->course_id) : route('admin.dashboard'),
                'action_text' => $module ? 'Voir le cours' : 'Dashboard',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route($module ? 'admin.courses.show' : 'admin.dashboard', $module ? $module->course_id : null)
                ->with('success', 'Quiz supprimé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'quiz', $quiz->title);
        }
    }
    
    /**
     * Show the form for creating questions for a quiz.
     * 
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\View\View
     */
    public function quizQuestionsCreate(Quiz $quiz)
    {
        return view('admin.quizzes.questions.create', compact('quiz'));
    }
    
    /**
     * Store a newly created quiz question.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\RedirectResponse
     */
    public function quizQuestionsStore(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'required_if:type,multiple_choice|array',
            'correct_answer' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'explanation' => 'nullable|string',
        ]);
        
        try {
            $question = new Question();
            $question->quiz_id = $quiz->id;
            $question->text = $validated['question']; // Utilise text au lieu de question
            $question->type = $validated['type'];
            
            if ($validated['type'] === 'multiple_choice') {
                $question->options = json_encode($validated['options']);
            } elseif ($validated['type'] === 'true_false') {
                $question->options = json_encode(['true', 'false']);
            }
            
            $question->correct_answer = $validated['correct_answer'];
            $question->points = $validated['points'] ?? 1;
            $question->explanation = $validated['explanation'] ?? null;
            $question->save();
            
            // Get related module for redirect
            $module = null;
            if ($quiz->related_type === 'App\Models\Module') {
                $module = \App\Models\Module::find($quiz->related_id);
            }
            
            return redirect()->route('admin.quizzes.edit', $quiz->id)
                ->with('success', 'Question ajoutée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'question', $validated['question']);
        }
    }
    
    /**
     * Show the form for editing the specified module.
     * 
     * @param  \App\Models\Module  $module
     * @return \Illuminate\View\View
     */
    public function modulesEdit(Module $module)
    {
        $course = $module->course;
        return view('admin.modules.edit', compact('module', 'course'));
    }
    
    /**
     * Update the specified module.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modulesUpdate(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
        ]);
        
        try {
            $module->update($validated);
            
            // Create notification
            NotificationService::userAction('updated', 'module', $module->title, [
                'model_id' => $module->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book',
                'color' => 'info'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Module mis à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'module', $module->title);
        }
    }
    
    /**
     * Store a newly created module.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modulesStore(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
        ]);
        
        try {
            $module = Module::create($validated);
            
            // Create notification
            NotificationService::userAction('created', 'module', $module->title, [
                'model_id' => $module->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Module ajouté avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'module', $validated['title']);
        }
    }
    
    /**
     * Remove the specified module.
     * 
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modulesDestroy(Module $module)
    {
        try {
            $courseId = $module->course_id;
            $title = $module->title;
            
            // Delete all lessons and quizzes associated with this module
            foreach ($module->lessons as $lesson) {
                if ($lesson->type === 'pdf' && \Illuminate\Support\Facades\Storage::disk('public')->exists($lesson->content)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($lesson->content);
                }
                $lesson->delete();
            }
            
            // Delete all quizzes
            $module->quizzes()->delete();
            
            // Delete the module
            $module->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'module', $title, [
                'action_url' => route('admin.courses.show', $courseId),
                'action_text' => 'Voir le cours',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route('admin.courses.show', $courseId)
                ->with('success', 'Module supprimé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'module', $module->title);
        }
    }
    
    /**
     * Show the form for editing the specified lesson.
     * 
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\View\View
     */
    public function lessonsEdit(Lesson $lesson)
    {
        $module = $lesson->module;
        $course = $module->course;
        return view('admin.lessons.edit', compact('lesson', 'module', 'course'));
    }
    
    /**
     * Update the specified lesson.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lessonsUpdate(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:video,text,pdf,external',
            'order' => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'video_url' => 'nullable|required_if:content_type,video|url',
            'text_content' => 'nullable|required_if:content_type,text|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'external_url' => 'nullable|required_if:content_type,external|url',
            'is_previewable' => 'nullable|boolean',
        ]);
        
        try {
            $lesson->title = $validated['title'];
            $lesson->content_type = $validated['content_type'];
            $lesson->order = $validated['order'] ?? $lesson->order;
            $lesson->duration_minutes = $validated['duration_minutes'] ?? $lesson->duration_minutes;
            $lesson->is_previewable = $request->has('is_previewable');
            
            // Handle content based on type
            if ($validated['content_type'] === 'video') {
                $lesson->video_url = $validated['video_url'];
                $lesson->text_content = null;
                $lesson->pdf_path = null;
                $lesson->external_url = null;
            } elseif ($validated['content_type'] === 'text') {
                $lesson->video_url = null;
                $lesson->text_content = $validated['text_content'];
                $lesson->pdf_path = null;
                $lesson->external_url = null;
            } elseif ($validated['content_type'] === 'pdf') {
                if ($request->hasFile('pdf_file')) {
                    // Delete old file if exists
                    if ($lesson->pdf_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($lesson->pdf_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($lesson->pdf_path);
                    }
                    $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                    $lesson->pdf_path = $path;
                }
                $lesson->video_url = null;
                $lesson->text_content = null;
                $lesson->external_url = null;
            } elseif ($validated['content_type'] === 'external') {
                $lesson->video_url = null;
                $lesson->text_content = null;
                $lesson->pdf_path = null;
                $lesson->external_url = $validated['external_url'];
            }
            
            $lesson->save();
            
            // Get module and course for redirect
            $module = $lesson->module;
            
            // Create notification
            NotificationService::userAction('updated', 'lesson', $lesson->title, [
                'model_id' => $lesson->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book-open',
                'color' => 'info'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon mise à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'lesson', $lesson->title);
        }
    }
    
    /**
     * Remove the specified lesson.
     * 
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lessonsDestroy(Lesson $lesson)
    {
        try {
            $module = $lesson->module;
            $title = $lesson->title;
            
            // Delete PDF file if exists
            if ($lesson->content_type === 'pdf' && $lesson->pdf_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($lesson->pdf_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($lesson->pdf_path);
            }
            
            // Delete any related resources if needed
            foreach ($lesson->resources as $resource) {
                // Delete file if it's stored on the server
                if ($resource->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($resource->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($resource->file_path);
                }
                // Delete the resource
                $resource->delete();
            }
            
            // Delete the lesson
            $lesson->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'lesson', $title, [
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon supprimée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'lesson', $lesson->title);
        }
    }
    
    /**
     * Store a newly created lesson.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Display the specified lesson.
     * 
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\View\View
     */
    public function lessonsShow(Lesson $lesson)
    {
        $module = $lesson->module;
        $course = $module->course;
        $resources = $lesson->resources;
        
        return view('admin.lessons.show', compact('lesson', 'module', 'course', 'resources'));
    }
    
    /**
     * Display the form to create a new lesson.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function lessonsCreate(Request $request)
    {
        $moduleId = $request->module_id;
        $module = Module::findOrFail($moduleId);
        $course = $module->course;
        
        return view('admin.lessons.create', compact('module', 'course'));
    }
    
    /**
     * Store a newly created lesson.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lessonsStore(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:video,text,pdf,external',
            'order' => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'video_url' => 'nullable|required_if:content_type,video|url',
            'text_content' => 'nullable|required_if:content_type,text|string',
            'pdf_file' => 'nullable|required_if:content_type,pdf|file|mimes:pdf|max:10240',
            'external_url' => 'nullable|required_if:content_type,external|url',
            'is_previewable' => 'nullable|boolean',
        ]);
        
        try {
            $lesson = new Lesson();
            $lesson->module_id = $validated['module_id'];
            $lesson->title = $validated['title'];
            $lesson->content_type = $validated['content_type'];
            $lesson->order = $validated['order'] ?? Lesson::where('module_id', $validated['module_id'])->max('order') + 1;
            $lesson->duration_minutes = $validated['duration_minutes'] ?? null;
            $lesson->is_previewable = $request->has('is_previewable');
            
            // Handle content based on type
            if ($validated['content_type'] === 'video') {
                $lesson->video_url = $validated['video_url'];
            } elseif ($validated['content_type'] === 'text') {
                $lesson->text_content = $validated['text_content'];
            } elseif ($validated['content_type'] === 'pdf' && $request->hasFile('pdf_file')) {
                $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                $lesson->pdf_path = $path;
            } elseif ($validated['content_type'] === 'external') {
                $lesson->external_url = $validated['external_url'];
            }
            
            $lesson->save();
            
            // Get module to access course
            $module = Module::findOrFail($validated['module_id']);
            
            // Create notification
            NotificationService::userAction('created', 'lesson', $lesson->title, [
                'model_id' => $lesson->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book-open',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon ajoutée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'lesson', $validated['title']);
        }
    }
}
