<?php

use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
// Removed: use App\Http\Controllers\RegisterUserController;
// Removed: use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\FormateurController;
use App\Http\Controllers\Auth\SocialAuthController as AuthSocialAuthController;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route pour la page d'accueil
Route::get('/', [CourseController::class, 'home'])->name('home');

// Routes pour les cours
Route::resource('courses', CourseController::class);
Route::get('/coursest/search', [CourseController::class, 'search'])->name('courses.search'); // Route pour la recherche avancée

// Ces routes sont nécessaires pour la compatibilité avec le code existant
Route::get('/cours', [CourseController::class, 'index'])->name('courses.index');
Route::get('/cours/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/categories/{category:slug}', [CourseController::class, 'byCategory'])->name('courses.category');

// Routes pour l'authentification sociale (Breeze)
Route::middleware('guest')->group(function () {
    Route::get('auth/{provider}', [AuthSocialAuthController::class, 'redirectToProvider'])
        ->name('social.login');
    Route::get('auth/{provider}/callback', [AuthSocialAuthController::class, 'handleProviderCallback']);
});

// Routes pour la complétion du profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/complete', [ProfileCompletionController::class, 'showForm'])
        ->name('profile.complete');
    Route::post('/profile/complete', [ProfileCompletionController::class, 'complete'])
        ->name('profile.complete.store');
});

// Dashboard central avec redirection intelligente
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->middleware(\App\Http\Middleware\ProfileCompletedMiddleware::class)
    ->name('dashboard');

// --- Routes d'Authentification ---
require __DIR__.'/auth.php'; // Si vous utilisez Breeze

// --- Routes de l'Administration (Toutes gérées par AdminController) ---
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]) // Protection de route
    ->name('admin.')
    ->group(function () {

        // Tableau de Bord
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard'); // Les deux routes utilisent la même méthode

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

// Routes pour formateurs
Route::middleware(['auth', \App\Http\Middleware\FormateurMiddleware::class])->prefix('formateur')->group(function () {
    // Tableau de bord principal
    Route::get('/dashboard', [FormateurController::class, 'index'])->name('formateur.dashboard');
    
    // Gestion des cours
    Route::get('/cours/creer', [FormateurController::class, 'createCourse'])->name('formateur.courses.create');
    Route::post('/cours/creer', [FormateurController::class, 'storeCourse'])->name('formateur.courses.store');
    Route::get('/cours/{courseId}/modifier', [FormateurController::class, 'editCourse'])->name('formateur.courses.edit');
    Route::put('/cours/{courseId}/modifier', [FormateurController::class, 'updateCourse'])->name('formateur.courses.update');
    Route::get('/cours/{courseId}/gerer', [FormateurController::class, 'manageCourse'])->name('formateur.manage.course');
    Route::post('/cours/{courseId}/publier', [FormateurController::class, 'publishCourse'])->name('formateur.courses.publish');
    
    // Gestion des modules
    Route::get('/cours/{courseId}/modules/creer', [FormateurController::class, 'createModule'])->name('formateur.modules.create');
    Route::post('/cours/{courseId}/modules/creer', [FormateurController::class, 'storeModule'])->name('formateur.modules.store');
    Route::get('/modules/{moduleId}/gerer', [FormateurController::class, 'manageModule'])->name('formateur.manage.module');
    
    // Gestion des leçons
    Route::get('/modules/{moduleId}/lecons/creer', [FormateurController::class, 'createLesson'])->name('formateur.lessons.create');
    Route::post('/modules/{moduleId}/lecons/creer', [FormateurController::class, 'storeLesson'])->name('formateur.lessons.store');
    
    // Gestion des quiz
    Route::get('/modules/{moduleId}/quiz/creer', [FormateurController::class, 'createQuiz'])->name('formateur.quizzes.create');
    Route::post('/modules/{moduleId}/quiz/creer', [FormateurController::class, 'storeQuiz'])->name('formateur.quizzes.store');
    
    // Gestion des étudiants et statistiques
    Route::get('/cours/{courseId}/etudiants', [FormateurController::class, 'courseStudents'])->name('formateur.courses.students');
    Route::get('/cours/{courseId}/evaluations', [FormateurController::class, 'courseRatings'])->name('formateur.courses.ratings');
    Route::post('/cours/evaluations/{ratingId}/repondre', [FormateurController::class, 'replyToRating'])->name('formateur.courses.ratings.reply');
    Route::get('/cours/{courseId}/revenus', [FormateurController::class, 'courseRevenues'])->name('formateur.courses.revenues');
    Route::get('/cours/{courseId}/revenus/exporter', [FormateurController::class, 'exportRevenues'])->name('formateur.courses.revenues.export');
});

// Routes pour apprenants
Route::middleware(['auth', \App\Http\Middleware\ApprenantMiddleware::class])->prefix('apprenant')->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [EtudiantController::class, 'index'])->name('apprenant.dashboard');
    
    // Profil
    Route::get('/profile', [EtudiantController::class, 'showProfile'])->name('apprenant.profile');
    Route::put('/profile', [EtudiantController::class, 'updateProfile'])->name('apprenant.profile.update');
    
    // Cours et leçons
    Route::get('/course/{courseId}', [EtudiantController::class, 'accessCourse'])->name('apprenant.course.access');
    Route::get('/lesson/{lessonId}', [EtudiantController::class, 'showLesson'])->name('apprenant.lesson');
    Route::post('/lesson/{lessonId}/complete', [EtudiantController::class, 'completeLesson'])->name('apprenant.lesson.complete');
    
    // Certifications
    Route::get('/certification/{certificationId}', [EtudiantController::class, 'downloadCertification'])->name('apprenant.certification.download');
});

Route::get('/apprenants', [EtudiantController::class, 'index']);

Route::resource('etudiants', EtudiantController::class);

// Les routes d'inscription classique sont maintenant gérées par Breeze (voir routes/auth.php)

// Routes pour les authentifications sociales - nous utilisons la version dans le namespace Auth
// Route::get('login/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.login');
// Route::get('login/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

// Pages diverses
Route::get('/Contactez-nous', [CoursController::class, 'contact'])->name('pages.contact');
Route::get('/les-cours-des-experts', [CoursController::class, 'coursE'])->name('pages.coursE');
Route::get('/les-cours-des-intermédiaires', [CoursController::class, 'coursT'])->name('pages.coursT');
Route::get('/les-cours-des-débutants', [CoursController::class, 'coursD'])->name('pages.coursD');
Route::get('/courses/free', [CoursController::class, 'free'])->name('courses.free');
Route::get('/courses/premium', [CoursController::class, 'premium'])->name('courses.premium');

//les fonctionnalites 
Route::get('/les-compétitions-disponibles', [CoursController::class, 'compdisp'])->name('pages.compdisp');
Route::get('/test-de-niveau', [CoursController::class, 'test'])->name('pages.test');
Route::get('/vérifier-un-certificat', [CoursController::class, 'verifier'])->name('pages.verifier');
Route::get('/le-forum-des-experts', [CoursController::class, 'forumexp'])->name('pages.forumexp');
Route::get('/le-forum-des-apprenants', [CoursController::class, 'forumapp'])->name('pages.forumapp');
Route::get('/a-propos-de-AfriCode', [CoursController::class, 'apropos'])->name('pages.apropos');

// Routes de profil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes paramétrages (Volt)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});
