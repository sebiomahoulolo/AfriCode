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
         return view('admin.courses.create');
    }

    public function coursesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => 'required|string|unique:courses,slug',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
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
         return view('admin.courses.edit', compact('course'));
    }

    public function coursesUpdate(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => 'required|string|unique:courses,slug,' . $course->id,
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
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
}
