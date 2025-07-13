<?php

use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\FormateurController;
use App\Http\Controllers\Auth\SocialAuthController as AuthSocialAuthController;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CoursePrerequisiteController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\CertificateVerificationController;

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

// Routes pour les inscriptions et paiements
Route::middleware(['auth'])->group(function () {
    // Inscription aux cours
    Route::get('/enrollment/{course}', [\App\Http\Controllers\EnrollmentController::class, 'show'])
        ->name('enrollment.show');
    Route::post('/enrollment/{course}/process', [\App\Http\Controllers\EnrollmentController::class, 'store'])
        ->name('enrollment.process-payment');
    
    // Paiements
    Route::get('/payment/success', [\App\Http\Controllers\EnrollmentController::class, 'paymentSuccess'])
        ->name('payment.success');
    Route::get('/payment/failed', [\App\Http\Controllers\EnrollmentController::class, 'paymentFailed'])
        ->name('payment.failed');
    
    // Traitement paiement Stripe côté client
    Route::post('/payment/stripe/process', [\App\Http\Controllers\EnrollmentController::class, 'processStripePayment'])
        ->name('payment.stripe.process');
});

// Webhooks et callbacks (pas de middleware auth)
Route::post('/webhook/stripe', [\App\Http\Controllers\EnrollmentController::class, 'stripeWebhook'])
    ->name('webhook.stripe');
Route::post('/callback/fadapay', [\App\Http\Controllers\EnrollmentController::class, 'fadapayCallback'])
    ->name('payment.fadapay.callback');

// Dashboard central avec redirection intelligente
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->middleware(\App\Http\Middleware\ProfileCompletedMiddleware::class)
    ->name('dashboard');

// Routes pour la vérification des certificats
Route::match(['get', 'post'], '/verifier-certificat/{verification_code?}', [CertificateVerificationController::class, 'verifyCertificate'])
    ->name('verification.form');
Route::match(['get', 'post'], '/verification-certificat', [CertificateVerificationController::class, 'verifyCertificate'])
    ->name('certificate.verification');
Route::get('/c/{certification:verification_code}', [CertificateVerificationController::class, 'showPublicCertificate'])->name('public.certificate.show');

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
        
        // --- Modules ---
        Route::post('/modules', [AdminController::class, 'modulesStore'])->name('modules.store');
        Route::get('/modules/{module}/edit', [AdminController::class, 'modulesEdit'])->name('modules.edit');
        Route::put('/modules/{module}', [AdminController::class, 'modulesUpdate'])->name('modules.update');
        Route::delete('/modules/{module}', [AdminController::class, 'modulesDestroy'])->name('modules.destroy');
        
        // --- Leçons ---
        Route::get('/lessons/create', [AdminController::class, 'lessonsCreate'])->name('lessons.create');
        Route::post('/lessons', [AdminController::class, 'lessonsStore'])->name('lessons.store');
        Route::get('/lessons/{lesson}', [AdminController::class, 'lessonsShow'])->name('lessons.show');
        Route::get('/lessons/{lesson}/edit', [AdminController::class, 'lessonsEdit'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [AdminController::class, 'lessonsUpdate'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [AdminController::class, 'lessonsDestroy'])->name('lessons.destroy');
        
        // --- Quiz ---
        Route::get('/quizzes/create', [AdminController::class, 'quizzesCreate'])->name('quizzes.create');
        Route::post('/quizzes', [AdminController::class, 'quizzesStore'])->name('quizzes.store');
        Route::get('/quizzes/{quiz}', [AdminController::class, 'quizzesShow'])->name('quizzes.show');
        Route::get('/quizzes/{quiz}/edit', [AdminController::class, 'quizzesEdit'])->name('quizzes.edit');
        Route::put('/quizzes/{quiz}', [AdminController::class, 'quizzesUpdate'])->name('quizzes.update');
        Route::delete('/quizzes/{quiz}', [AdminController::class, 'quizzesDestroy'])->name('quizzes.destroy');
        Route::get('/quizzes/{quiz}/questions/create', [AdminController::class, 'quizQuestionsCreate'])->name('quiz.questions.create');
        Route::post('/quizzes/{quiz}/questions', [AdminController::class, 'quizQuestionsStore'])->name('quiz.questions.store');
        
        // --- Questions du Quiz ---
        Route::get('/questions/{question}/edit', [AdminController::class, 'questionEdit'])->name('questions.edit');
        Route::put('/questions/{question}', [AdminController::class, 'questionUpdate'])->name('questions.update');
        Route::delete('/questions/{question}', [AdminController::class, 'questionDestroy'])->name('questions.destroy');

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
        
        // --- Upload d'images pour TinyMCE ---
        Route::post('/upload/image', [AdminImageController::class, 'upload'])->name('admin.upload.image');

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
    Route::delete('/cours/{courseId}', [FormateurController::class, 'destroyCourse'])->name('formateur.courses.destroy');
    
    // Gestion des modules
    Route::get('/cours/{courseId}/modules/creer', [FormateurController::class, 'createModule'])->name('formateur.modules.create');
    Route::post('/cours/{courseId}/modules/creer', [FormateurController::class, 'storeModule'])->name('formateur.modules.store');
    Route::get('/modules/{moduleId}/gerer', [FormateurController::class, 'manageModule'])->name('formateur.manage.module');
    
    // Gestion des leçons
    Route::get('/modules/{moduleId}/lecons/creer', [FormateurController::class, 'createLesson'])->name('formateur.lessons.create');
    Route::post('/modules/{moduleId}/lecons/creer', [FormateurController::class, 'storeLesson'])->name('formateur.lessons.store');
    Route::get('/lecons/{lessonId}/editer', [FormateurController::class, 'editLesson'])->name('formateur.lessons.edit');
    Route::put('/lecons/{lessonId}/editer', [FormateurController::class, 'updateLesson'])->name('formateur.lessons.update');
    Route::delete('/lecons/{lessonId}', [FormateurController::class, 'destroyLesson'])->name('formateur.lessons.destroy');
    
    // Gestion des quiz
    Route::get('/modules/{moduleId}/quiz/creer', [FormateurController::class, 'createQuiz'])->name('formateur.quizzes.create');
    Route::post('/modules/{moduleId}/quiz/creer', [FormateurController::class, 'storeQuiz'])->name('formateur.quizzes.store');
    Route::get('/quiz/{quizId}/editer', [FormateurController::class, 'editQuiz'])->name('formateur.quizzes.edit');
    Route::put('/quiz/{quizId}/editer', [FormateurController::class, 'updateQuiz'])->name('formateur.quizzes.update');
    Route::delete('/quiz/{quizId}', [FormateurController::class, 'destroyQuiz'])->name('formateur.quizzes.destroy');
    
    // Gestion des examens finaux
    Route::get('/cours/{courseId}/examen-final/creer', [FormateurController::class, 'createFinalExam'])->name('formateur.final-exam.create');
    Route::post('/cours/{courseId}/examen-final/creer', [FormateurController::class, 'storeFinalExam'])->name('formateur.final-exam.store');
    Route::get('/cours/{courseId}/examen-final/editer', [FormateurController::class, 'editFinalExam'])->name('formateur.final-exam.edit');
    Route::put('/cours/{courseId}/examen-final/editer', [FormateurController::class, 'updateFinalExam'])->name('formateur.final-exam.update');
    Route::delete('/cours/{courseId}/examen-final', [FormateurController::class, 'destroyFinalExam'])->name('formateur.final-exam.destroy');
    
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
    
    // Mes cours
    Route::get('/mes-cours', [EtudiantController::class, 'myCourses'])->name('apprenant.courses');
    
    // Cours et leçons
    Route::get('/course/{courseId}', [EtudiantController::class, 'accessCourse'])->name('apprenant.course.access');
    Route::get('/lesson/{lessonId}', [EtudiantController::class, 'showLesson'])->name('apprenant.lesson');
    Route::post('/lesson/{lessonId}/complete', [EtudiantController::class, 'completeLesson'])->name('apprenant.lesson.complete');
    
    // Quiz
    Route::get('/quiz/{quizId}', [EtudiantController::class, 'showQuiz'])->name('apprenant.quiz.show');
    Route::get('/quiz/{quizId}/start', [EtudiantController::class, 'startQuiz'])->name('apprenant.quiz.start');
    Route::post('/quiz/{quizId}/take', [EtudiantController::class, 'takeQuiz'])->name('apprenant.quiz.take');
    Route::get('/quiz/{quizId}/result/{attemptId}', [EtudiantController::class, 'showQuizResult'])->name('apprenant.quiz.result');
    
    // Certifications
    Route::get('/certifications', [EtudiantController::class, 'showCertifications'])->name('apprenant.certifications');
    Route::get('/certification/{certificationId}', [EtudiantController::class, 'downloadCertification'])->name('apprenant.certification.download');
    Route::get('/apprenant/certification/{certificationId}/view', [EtudiantController::class, 'showCertification'])->name('apprenant.certification.view');
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
Route::get('/les-compétitions-disponibles', [\App\Http\Controllers\CompetitionDisplayController::class, 'index'])->name('pages.compdisp');
Route::get('/api/leaderboard', [\App\Http\Controllers\CompetitionDisplayController::class, 'getLeaderboard'])->name('api.leaderboard');
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

// Routes pour le système de paiement
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/initiate/{course}', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
    Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
    Route::post('/payment/{payment}/refund', [PaymentController::class, 'refund'])->name('payment.refund');
    Route::get('/payment/history', [PaymentController::class, 'paymentHistory'])->name('payment.history');
});

// Routes pour le chat
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{user}/{course}', [ChatController::class, 'show'])->name('chat.show.course');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
    Route::patch('/chat/{message}/read', [ChatController::class, 'markAsRead'])->name('chat.read');
    Route::delete('/chat/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');
});

// Routes pour la gamification
Route::middleware(['auth'])->group(function () {
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/achievements/{achievement}', [AchievementController::class, 'show'])->name('achievements.show');
    Route::get('/leaderboard', [AchievementController::class, 'leaderboard'])->name('achievements.leaderboard');
});

// Routes pour le support
Route::middleware(['auth'])->group(function () {
    // Tickets de support
    Route::get('/support/tickets', [SupportController::class, 'tickets'])->name('support.tickets.index');
    Route::get('/support/tickets/create', [SupportController::class, 'createTicket'])->name('support.tickets.create');
    Route::post('/support/tickets', [SupportController::class, 'storeTicket'])->name('support.tickets.store');
    Route::get('/support/tickets/{ticket}', [SupportController::class, 'showTicket'])->name('support.tickets.show');
    Route::post('/support/tickets/{ticket}/reply', [SupportController::class, 'replyTicket'])->name('support.tickets.reply');

    // FAQ
    Route::get('/support/faq', [SupportController::class, 'faq'])->name('support.faq.index');
    Route::get('/support/faq/{article}', [SupportController::class, 'showFaqArticle'])->name('support.faq.show');
    Route::post('/support/faq/{article}/helpful', [SupportController::class, 'markFaqHelpful'])->name('support.faq.helpful');
    Route::post('/support/faq/{article}/not-helpful', [SupportController::class, 'markFaqNotHelpful'])->name('support.faq.not-helpful');

    // Administration du support
    Route::middleware(['can:manageSupport'])->group(function () {
        Route::get('/admin/support/tickets', [SupportController::class, 'adminTickets'])->name('admin.support.tickets.index');
        Route::post('/admin/support/tickets/{ticket}/assign', [SupportController::class, 'assignTicket'])->name('admin.support.tickets.assign');
        Route::patch('/admin/support/tickets/{ticket}/status', [SupportController::class, 'updateTicketStatus'])->name('admin.support.tickets.status');
    });
});

// Routes pour les quiz
Route::middleware(['auth'])->group(function () {
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    
    // Routes pour les tentatives de quiz
    Route::post('/quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::get('/quiz-attempts/{attempt}', [QuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quiz-attempts/{attempt}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quiz-attempts/{attempt}/results', [QuizController::class, 'results'])->name('quizzes.results');

    // Routes pour les questions de quiz
    Route::get('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'index'])->name('quizzes.questions.index');
    Route::post('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])->name('quizzes.questions.store');
    Route::put('/quizzes/{quiz}/questions/{question}', [QuizQuestionController::class, 'update'])->name('quizzes.questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuizQuestionController::class, 'destroy'])->name('quizzes.questions.destroy');
    Route::post('/quizzes/{quiz}/questions/reorder', [QuizQuestionController::class, 'reorder'])->name('quizzes.questions.reorder');
});

// Routes pour les badges et récompenses
Route::middleware(['auth'])->group(function () {
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');
    Route::get('/badges/{badge}', [BadgeController::class, 'show'])->name('badges.show');
    Route::get('/users/{user}/badges', [BadgeController::class, 'userBadges'])->name('badges.user');
    Route::post('/badges/check-progress', [BadgeController::class, 'checkProgress'])->name('badges.check-progress');
});

// Rewards Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/{reward}', [RewardController::class, 'show'])->name('rewards.show');
    Route::post('/rewards/{reward}/claim', [RewardController::class, 'claim'])->name('rewards.claim');
    Route::get('/users/{user}/rewards', [RewardController::class, 'userRewards'])->name('rewards.user');
    Route::get('/rewards/check-available', [RewardController::class, 'checkAvailable'])->name('rewards.check-available');
});

// Routes pour les prérequis des cours
Route::middleware(['auth'])->group(function () {
    Route::get('/courses/{course}/prerequisites', [CoursePrerequisiteController::class, 'index'])->name('courses.prerequisites.index');
    Route::get('/courses/{course}/prerequisites/create', [CoursePrerequisiteController::class, 'create'])->name('courses.prerequisites.create');
    Route::post('/courses/{course}/prerequisites', [CoursePrerequisiteController::class, 'store'])->name('courses.prerequisites.store');
    Route::get('/courses/{course}/prerequisites/{prerequisite}/edit', [CoursePrerequisiteController::class, 'edit'])->name('courses.prerequisites.edit');
    Route::put('/courses/{course}/prerequisites/{prerequisite}', [CoursePrerequisiteController::class, 'update'])->name('courses.prerequisites.update');
    Route::delete('/courses/{course}/prerequisites/{prerequisite}', [CoursePrerequisiteController::class, 'destroy'])->name('courses.prerequisites.destroy');
    Route::get('/courses/{course}/check-access', [CoursePrerequisiteController::class, 'checkAccess'])->name('courses.check-access');
});

// Forum de discussion (accessible à tous les utilisateurs connectés)
Route::middleware(['auth'])->prefix('forum')->name('forum.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CourseForumController::class, 'index'])->name('index');
    Route::get('/creer', [\App\Http\Controllers\CourseForumController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\CourseForumController::class, 'store'])->name('store');
    Route::get('/{id}', [\App\Http\Controllers\CourseForumController::class, 'show'])->name('show');
    Route::get('/{id}/editer', [\App\Http\Controllers\CourseForumController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\CourseForumController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\CourseForumController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/repondre', [\App\Http\Controllers\CourseForumController::class, 'reply'])->name('reply');
});

// Route personnalisée pour le forum des apprenants (accès direct)
Route::get('/le-forum-des-apprenants', [\App\Http\Controllers\CourseForumController::class, 'index'])->name('pages.forumapp');

// Route personnalisée pour afficher un sujet du forum des experts
Route::get('/le-forum-des-experts/{id}', [\App\Http\Controllers\CourseForumController::class, 'show'])->name('pages.forumexp');

// Redirection automatique de /le-forum-des-experts vers la liste des sujets du forum
Route::get('/le-forum-des-experts', function () {
    return redirect()->route('forum.index'); // ou 'pages.forumapp' si tu préfères
});

Route::middleware(['auth'])->group(function () {
    Route::post('/forum/ajax-store', [\App\Http\Controllers\CourseForumController::class, 'storeAjax'])->name('forum.ajaxStore');
    Route::get('/api/forum/topics', [\App\Http\Controllers\CourseForumController::class, 'apiTopics'])->name('api.forum.topics');
    Route::get('/api/forum/topic/{id}', [\App\Http\Controllers\CourseForumController::class, 'apiTopicDetail'])->name('api.forum.topicDetail');
    Route::post('/api/forum/topic/{id}/reply', [\App\Http\Controllers\CourseForumController::class, 'apiReply'])->name('api.forum.reply');
});

// API pour le leaderboard du forum
Route::get('/api/forum/leaderboard', function () {
    try {
        // Utiliser la même logique que CompetitionDisplayController
        $globalLeaderboard = \App\Models\Leaderboard::where('type', 'global')->first();
        $leaderboardData = [];
        
        if ($globalLeaderboard) {
            $leaderboardData = \App\Models\UserScore::with('user')
                ->where('leaderboard_id', $globalLeaderboard->id)
                ->orderBy('score', 'desc')
                ->take(3)
                ->get()
                ->map(function ($score, $index) {
                    // Utiliser la même logique que CompetitionDisplayController
                    $user = $score->user;
                    $name = $user->first_name . ' ' . $user->last_name;
                    
                    // Générer l'avatar comme dans CompetitionDisplayController
                    $initials = strtoupper(substr($name, 0, 2));
                    $colors = ['#1EA38B', '#FF8E2A', '#E32D31', '#27B371', '#9B59B6'];
                    $color = $colors[array_rand($colors)];
                    $avatar = "https://via.placeholder.com/35/{$color}/FFFFFF?text=" . urlencode($initials);
                    
                    // Récupérer les badges récents
                    $recentBadges = \Illuminate\Support\Facades\DB::table('badge_user')
                        ->join('badges', 'badge_user.badge_id', '=', 'badges.id')
                        ->where('badge_user.user_id', $user->id)
                        ->orderBy('badge_user.awarded_at', 'desc')
                        ->limit(3)
                        ->pluck('badges.icon')
                        ->toArray();

                    return [
                        'rank' => $index + 1,
                        'id' => $user->id,
                        'name' => $name,
                        'avatar' => $avatar,
                        'score' => $score->score,
                        'recentBadges' => $recentBadges
                    ];
                });
        }

        // Si pas de données, retourner des données de test avec la même structure
        if (empty($leaderboardData)) {
            $leaderboardData = [
                [
                    'rank' => 1, 
                    'id' => 5, 
                    'name' => 'Amina D.', 
                    'avatar' => 'https://via.placeholder.com/35/FF8E2A/FFFFFF?text=AD', 
                    'score' => 1520,
                    'recentBadges' => ['fa-trophy', 'fa-star']
                ],
                [
                    'rank' => 2, 
                    'id' => 23, 
                    'name' => 'Kwame N.', 
                    'avatar' => 'https://via.placeholder.com/35/E32D31/FFFFFF?text=KN', 
                    'score' => 1480,
                    'recentBadges' => ['fa-medal']
                ],
                [
                    'rank' => 3, 
                    'id' => 12, 
                    'name' => 'Fatou S.', 
                    'avatar' => 'https://via.placeholder.com/35/27B371/FFFFFF?text=FS', 
                    'score' => 1350,
                    'recentBadges' => ['fa-award']
                ],
            ];
        }

        return response()->json(['success' => true, 'leaderboard' => $leaderboardData]);
    } catch (\Exception $e) {
        \Log::error('Erreur API leaderboard forum: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => 'Erreur serveur'], 500);
    }
})->name('api.forum.leaderboard');

