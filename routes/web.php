<?php
    use App\Http\Controllers\EtudiantController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\RegisterUserController;
    use App\Http\Controllers\SocialAuthController;
    use App\Http\Controllers\CoursController;
    use App\Http\Controllers\Controller;
use Livewire\Volt\Volt;


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::get('/home', [Controller::class, 'index'])->name('home');

    Route::get('/', function () {
        return view('index');
    });

        
        // --- Routes d'Authentification ---
        // require __DIR__.'/auth.php'; // Si vous utilisez Breeze
        
        // --- Routes de l'Administration (Toutes gérées par AdminController) ---
        Route::prefix('admin')
            //->middleware(['auth', 'isAdmin']) // Toujours protéger !
            ->name('admin.')
            ->group(function () {
        
                // Tableau de Bord
                Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
                // --- Utilisateurs ---
                Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
                Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');
                Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
                Route::get('/users/{user}', [AdminController::class, 'usersShow'])->name('users.show');
                Route::get('/users/{user}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
                Route::put('/users/{user}', [AdminController::class, 'usersUpdate'])->name('users.update');
                Route::delete('/users/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');
                Route::get('/users/export', [AdminController::class, 'usersExport'])->name('users.export'); // Export
        
                // --- Cours ---
                Route::get('/courses', [AdminController::class, 'coursesIndex'])->name('courses.index');
                Route::get('/courses/create', [AdminController::class, 'coursesCreate'])->name('courses.create');
                Route::post('/courses', [AdminController::class, 'coursesStore'])->name('courses.store');
                Route::get('/courses/{course}', [AdminController::class, 'coursesShow'])->name('courses.show');
                Route::get('/courses/{course}/edit', [AdminController::class, 'coursesEdit'])->name('courses.edit');
                Route::put('/courses/{course}', [AdminController::class, 'coursesUpdate'])->name('courses.update');
                Route::delete('/courses/{course}', [AdminController::class, 'coursesDestroy'])->name('courses.destroy');
        
                // --- Certifications ---
                Route::get('/certifications', [AdminController::class, 'certificationsIndex'])->name('certifications.index');
                Route::get('/certifications/{certification}', [AdminController::class, 'certificationsShow'])->name('certifications.show');
                // Route::post('/certifications/generate', [AdminController::class, 'certificationsGenerate'])->name('certifications.generate');
        
                // --- Statistiques ---
                Route::get('/statistics', [AdminController::class, 'statisticsIndex'])->name('statistics.index');
        
                // --- Paiements ---
                Route::get('/payments', [AdminController::class, 'paymentsIndex'])->name('payments.index');
                Route::get('/payments/{payment}', [AdminController::class, 'paymentsShow'])->name('payments.show');
                Route::get('/payments/export', [AdminController::class, 'paymentsExport'])->name('payments.export'); // Export
        
                // --- Messages ---
                Route::get('/messages', [AdminController::class, 'messagesIndex'])->name('messages.index');
                Route::get('/messages/{message}', [AdminController::class, 'messagesShow'])->name('messages.show');
                Route::delete('/messages/{message}', [AdminController::class, 'messagesDestroy'])->name('messages.destroy');
                // Route::post('/messages/{message}/reply', [AdminController::class, 'messagesReply'])->name('messages.reply');
        
                // --- Événements (Calendrier) ---
                Route::get('/events', [AdminController::class, 'eventsIndex'])->name('events.index');
                Route::get('/events/create', [AdminController::class, 'eventsCreate'])->name('events.create');
                Route::post('/events', [AdminController::class, 'eventsStore'])->name('events.store');
                Route::get('/events/{event}', [AdminController::class, 'eventsShow'])->name('events.show'); // Optionnel, dépend si on affiche un détail
                Route::get('/events/{event}/edit', [AdminController::class, 'eventsEdit'])->name('events.edit');
                Route::put('/events/{event}', [AdminController::class, 'eventsUpdate'])->name('events.update');
                Route::delete('/events/{event}', [AdminController::class, 'eventsDestroy'])->name('events.destroy');
        
                // --- Tâches ---
                Route::get('/tasks', [AdminController::class, 'tasksIndex'])->name('tasks.index'); // Peut-être inutile si affiché sur dashboard
                Route::get('/tasks/create', [AdminController::class, 'tasksCreate'])->name('tasks.create');
                Route::post('/tasks', [AdminController::class, 'tasksStore'])->name('tasks.store');
                Route::get('/tasks/{task}/edit', [AdminController::class, 'tasksEdit'])->name('tasks.edit');
                Route::put('/tasks/{task}', [AdminController::class, 'tasksUpdate'])->name('tasks.update');
                Route::delete('/tasks/{task}', [AdminController::class, 'tasksDestroy'])->name('tasks.destroy');
                Route::patch('/tasks/{task}/complete', [AdminController::class, 'tasksMarkComplete'])->name('tasks.complete');
        
                // --- Notifications ---
                Route::get('/notifications', [AdminController::class, 'notificationsIndex'])->name('notifications.index'); // Peut-être inutile si affiché sur dashboard/layout
                Route::post('/notifications/mark-all-read', [AdminController::class, 'notificationsMarkAllRead'])->name('notifications.markAllRead');
                Route::patch('/notifications/{notification}/mark-read', [AdminController::class, 'notificationsMarkRead'])->name('notifications.markRead');
                Route::delete('/notifications/{notification}', [AdminController::class, 'notificationsDestroy'])->name('notifications.destroy');
        
                // --- Paramètres ---
                Route::get('/settings', [AdminController::class, 'settingsEdit'])->name('settings.edit');
                Route::put('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
        
            }); 

       
        Route::get('/apprenants', [EtudiantController::class, 'index']);
  






















































    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('etudiants', EtudiantController::class);
    // Route d'inscription classique
    Route::get('register', [RegisterUserController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterUserController::class, 'register']);
    
    // Routes pour les authentifications sociales
    Route::get('login/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.login');
    Route::get('login/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);
    Route::get('/Contactez-nous', [CoursController::class, 'contact'])->name('pages.contact');
    Route::get('/les cours des experts', [CoursController::class, 'coursE'])->name('pages.coursE');
    Route::get('/les cours des intermédiaires', [CoursController::class, 'coursT'])->name('pages.coursT');
    Route::get('/les cours des débutants', [CoursController::class, 'coursD'])->name('pages.coursD');
    Route::get('/courses/free', [CoursController::class, 'free'])->name('courses.free');
    Route::get('/courses/premium', [CoursController::class, 'premium'])->name('courses.premium');
    
    //les fonctionnalites 
    Route::get('/les compétitions disponibles', [CoursController::class, 'compdisp'])->name('pages.compdisp');
    Route::get('/test de niveau', [CoursController::class, 'test'])->name('pages.test');
    Route::get('/vérifier un certificat', [CoursController::class, 'verifier'])->name('pages.verifier');
    Route::get('/le forum des experts', [CoursController::class, 'forumexp'])->name('pages.forumexp');
    Route::get('/le forum des apprenants', [CoursController::class, 'forumapp'])->name('pages.forumapp');
    Route::get('/a propos de AfriCode', [CoursController::class, 'apropos'])->name('pages.apropos');
   
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    




Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
