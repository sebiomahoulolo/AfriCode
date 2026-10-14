<?php

use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
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
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\TutoringSessionController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CoursePrerequisiteController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminChallengeController;
use App\Http\Controllers\CompetitionDisplayController;
use App\Http\Controllers\AdminCompetitionTestCaseController;
use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\AdminFormateurController;
use App\Http\Controllers\FormateurEarningsController;

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

// Route pour la page d'accueil (sur mobile, redirection vers /cours)
Route::get('/', [CourseController::class, 'home'])->name('home')->middleware('mobile.redirect.cours');

// PWA : page offline (sans slash pour cohérence avec le cache du service worker)
Route::get('/offline', function () {
    return view('laravelpwa::offline');
})->name('laravelpwa.offline');

// Routes pour les cours
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search'); // Route pour la recherche avancée
Route::get('/courses-filter', [CourseController::class, 'filter'])->name('courses.filter'); // Route Ajax pour les filtres
Route::get('/courses-categories', [CourseController::class, 'categories'])->name('courses.categories'); // Route Ajax pour les catégories

// Routes pour la wishlist (favoris)
Route::middleware('auth')->group(function () {
    Route::post('/courses/{course}/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/courses/{course}/wishlist/check', [WishlistController::class, 'check'])->name('wishlist.check');
    Route::get('/courses/wishlist/check-batch', [WishlistController::class, 'checkBatch'])->name('wishlist.checkBatch');
});

// Ces routes sont nécessaires pour la compatibilité avec le code existant
Route::get('/cours', [CourseController::class, 'index'])->name('cours.index');
Route::get('/cours/{slug}', [CourseController::class, 'show'])->name('cours.show');
Route::get('/categories/{category:slug}', [CourseController::class, 'byCategory'])->name('courses.category');

// Routes pour l'authentification sociale (Breeze)
Route::middleware('guest')->group(function () {
    Route::get('auth/{provider}', [AuthSocialAuthController::class, 'redirectToProvider'])
        ->name('social.login');
    Route::get('auth/{provider}/callback', [AuthSocialAuthController::class, 'handleProviderCallback']);
});

// Vérification disponibilité email (inscription)
Route::post('/check-email', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'checkEmail'])
    ->name('register.checkEmail')
    ->middleware('throttle:20,1');

// Routes protégées par rate limiting (protection contre force brute)
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store']);
});

// Routes de paiement protégées
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/payment/initiate/{course}', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
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
    Route::post('/payment/cleanup-failed', [\App\Http\Controllers\EnrollmentController::class, 'cleanupFailedPayments'])
        ->name('payment.cleanup-failed');
    Route::post('/enrollment/cleanup-orphaned', [\App\Http\Controllers\EnrollmentController::class, 'cleanupOrphanedEnrollments'])
        ->name('enrollment.cleanup-orphaned');
    
    // Traitement paiement Stripe côté client
    Route::post('/payment/stripe/process', [\App\Http\Controllers\EnrollmentController::class, 'processStripePayment'])
        ->name('payment.stripe.process');
});

// Webhooks et callbacks (pas de middleware auth)
Route::post('/webhook/stripe', [\App\Http\Controllers\EnrollmentController::class, 'stripeWebhook'])
    ->name('webhook.stripe');

// Retour Moneroo après paiement (GET — redirection depuis la page de paiement Moneroo)
Route::get('/payment/moneroo/return', [\App\Http\Controllers\EnrollmentController::class, 'monerooReturn'])
    ->name('payment.moneroo.return');

// Routes PayPal
Route::get('/payment/paypal/success/{payment_id}', [\App\Http\Controllers\EnrollmentController::class, 'paypalSuccess'])
    ->name('payment.paypal.success');
Route::get('/payment/paypal/cancel/{payment_id}', [\App\Http\Controllers\EnrollmentController::class, 'paypalCancel'])
    ->name('payment.paypal.cancel');
Route::post('/webhook/paypal', [\App\Http\Controllers\EnrollmentController::class, 'paypalWebhook'])
    ->name('webhook.paypal');

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
        Route::get('/dashboard', [AdminController::class, 'dashboard']); // Alias sans nom pour éviter le doublon

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
        
        // --- Campagnes Email ---
        Route::get('/email-campaigns', [AdminController::class, 'emailCampaigns'])->name('email-campaigns.index');
        Route::post('/email-campaigns/{course}/send', [AdminController::class, 'sendEmailCampaign'])->name('email-campaigns.send');

        // --- Recrutement (postes + candidatures) ---
        Route::get('/recruitment', [\App\Http\Controllers\AdminRecruitmentController::class, 'index'])->name('recruitment.index');
        Route::get('/recruitment/applications', [\App\Http\Controllers\AdminRecruitmentController::class, 'applications'])->name('recruitment.applications.index');
        Route::get('/recruitment/applications/{application}/document/{type}', [\App\Http\Controllers\AdminRecruitmentController::class, 'document'])->name('recruitment.applications.document')->where('type', 'cv|lettre|diplome');
        Route::get('/recruitment/applications/{application}', [\App\Http\Controllers\AdminRecruitmentController::class, 'applicationShow'])->name('recruitment.applications.show');
        
        // --- Modules ---
        Route::post('/modules', [\App\Http\Controllers\ModuleLessonController::class, 'modulesStore'])->name('modules.store');
        Route::get('/modules/{module}/edit', [\App\Http\Controllers\ModuleLessonController::class, 'modulesEdit'])->name('modules.edit');
        Route::put('/modules/{module}', [\App\Http\Controllers\ModuleLessonController::class, 'modulesUpdate'])->name('modules.update');
        Route::delete('/modules/{module}', [\App\Http\Controllers\ModuleLessonController::class, 'modulesDestroy'])->name('modules.destroy');
        
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

        // --- Tracking / Analytics (où voir les résultats) ---
        Route::get('/tracking', [AdminController::class, 'trackingIndex'])->name('tracking.index');

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
        Route::match(['put', 'post'], '/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
        
        // --- Gestion des formateurs ---
        Route::prefix('formateurs')->name('formateurs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AdminFormateurController::class, 'index'])->name('index');
            
            // Retraits (DOIT être avant /{id} pour éviter les conflits)
            Route::get('/withdrawals', [\App\Http\Controllers\AdminFormateurController::class, 'withdrawals'])->name('withdrawals');
            Route::post('/withdrawals/{id}/process', [\App\Http\Controllers\AdminFormateurController::class, 'processWithdrawal'])->name('withdrawals.process');
            Route::post('/withdrawals/{id}/complete', [\App\Http\Controllers\AdminFormateurController::class, 'completeWithdrawal'])->name('withdrawals.complete');
            
            // Validation des cours
            Route::post('/courses/{courseId}/validate', [\App\Http\Controllers\AdminFormateurController::class, 'validateCourse'])->name('courses.validate');
            Route::post('/courses/{courseId}/revision', [\App\Http\Controllers\AdminFormateurController::class, 'requestRevision'])->name('courses.revision');
            Route::post('/courses/{courseId}/reject', [\App\Http\Controllers\AdminFormateurController::class, 'rejectCourse'])->name('courses.reject');
            
            // Routes avec paramètre {id} (DOIT être en dernier)
            Route::get('/{id}', [\App\Http\Controllers\AdminFormateurController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [\App\Http\Controllers\AdminFormateurController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\AdminFormateurController::class, 'reject'])->name('reject');
            Route::post('/{id}/suspend', [\App\Http\Controllers\AdminFormateurController::class, 'suspend'])->name('suspend');
            Route::post('/{id}/unsuspend', [\App\Http\Controllers\AdminFormateurController::class, 'unsuspend'])->name('unsuspend');
            Route::post('/{id}/warn', [\App\Http\Controllers\AdminFormateurController::class, 'warn'])->name('warn');
        });

        // --- Campagnes Push ---
        Route::prefix('push-campaigns')->name('push-campaigns.')->group(function () {
            Route::get('/',                                        [\App\Http\Controllers\Admin\PushCampaignController::class, 'index'])->name('index');
            Route::get('/create',                                  [\App\Http\Controllers\Admin\PushCampaignController::class, 'create'])->name('create');
            Route::post('/',                                       [\App\Http\Controllers\Admin\PushCampaignController::class, 'store'])->name('store');
            Route::get('/{pushCampaign}',                          [\App\Http\Controllers\Admin\PushCampaignController::class, 'show'])->name('show');
            Route::post('/{pushCampaign}/send',                    [\App\Http\Controllers\Admin\PushCampaignController::class, 'send'])->name('send');
            Route::delete('/{pushCampaign}',                       [\App\Http\Controllers\Admin\PushCampaignController::class, 'destroy'])->name('destroy');
        });

        // --- Support (tickets) — même accès que le reste de l'admin ---
        Route::get('/support/tickets', [SupportController::class, 'adminTickets'])->name('support.tickets.index');
        Route::post('/support/tickets/{ticket}/assign', [SupportController::class, 'assignTicket'])->name('support.tickets.assign');
        Route::patch('/support/tickets/{ticket}/status', [SupportController::class, 'updateTicketStatus'])->name('support.tickets.status');
    }); 

// Routes pour formateurs
Route::middleware(['auth', \App\Http\Middleware\FormateurMiddleware::class])->prefix('formateur')->name('formateur.')->group(function () {
    // Tableau de bord principal
    Route::get('/dashboard', [FormateurController::class, 'index'])->name('dashboard');
    
    // Liste des cours
    Route::get('/cours', [FormateurController::class, 'coursesList'])->name('courses.index');
    
    // Gestion des cours
    Route::get('/cours/creer', [FormateurController::class, 'createCourse'])->name('courses.create');
    Route::post('/cours/creer', [FormateurController::class, 'storeCourse'])->name('courses.store');
    Route::get('/cours/{courseId}/modifier', [FormateurController::class, 'editCourse'])->name('courses.edit');
    Route::put('/cours/{courseId}/modifier', [FormateurController::class, 'updateCourse'])->name('courses.update');
    Route::get('/cours/{courseId}/gerer', [FormateurController::class, 'manageCourse'])->name('manage.course');
    Route::post('/cours/{courseId}/publier', [FormateurController::class, 'publishCourse'])->name('courses.publish');
    Route::post('/cours/{courseId}/submit', [FormateurController::class, 'submitForValidation'])->name('courses.submit');
    Route::delete('/cours/{courseId}', [FormateurController::class, 'destroyCourse'])->name('courses.destroy');
    
    // Gestion des revenus
    Route::prefix('earnings')->name('earnings.')->group(function () {
        Route::get('/', [FormateurEarningsController::class, 'index'])->name('index');
        Route::post('/withdraw', [FormateurEarningsController::class, 'requestWithdrawal'])->name('withdraw');
        Route::get('/{id}', [FormateurEarningsController::class, 'show'])->name('show');
    });
    
    // Guide et documents
    Route::get('/guide', [FormateurController::class, 'showGuide'])->name('guide');
    
    // Gestion des modules
    Route::get('/cours/{courseId}/modules/creer', [FormateurController::class, 'createModule'])->name('modules.create');
    Route::post('/cours/{courseId}/modules/creer', [FormateurController::class, 'storeModule'])->name('modules.store');
    Route::get('/modules/{moduleId}/modifier', [FormateurController::class, 'editModule'])->name('modules.edit');
    Route::put('/modules/{moduleId}', [FormateurController::class, 'updateModule'])->name('modules.update');
    Route::get('/modules/{moduleId}/gerer', [FormateurController::class, 'manageModule'])->name('manage.module');
    
    // Gestion des leçons
    Route::get('/modules/{moduleId}/lecons/creer', [FormateurController::class, 'createLesson'])->name('lessons.create');
    Route::post('/modules/{moduleId}/lecons/creer', [FormateurController::class, 'storeLesson'])->name('lessons.store');
    Route::get('/lecons/{lessonId}/editer', [FormateurController::class, 'editLesson'])->name('lessons.edit');
    Route::put('/lecons/{lessonId}/editer', [FormateurController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/lecons/{lessonId}', [FormateurController::class, 'destroyLesson'])->name('lessons.destroy');
    
    // Voir la leçon comme un étudiant
    Route::get('/cours/{courseId}/lecon/{lessonId}/apercu', [FormateurController::class, 'viewLessonAsStudent'])->name('view.lesson');
    
    // Upload d'images pour CKEditor
    Route::post('/upload/image', [AdminImageController::class, 'upload'])->name('upload.image');
    
    // Route pour servir les images des leçons
    Route::get('/lesson-images/{filename}', function ($filename) {
        $path = storage_path('app/public/lesson_images/' . $filename);
        if (file_exists($path)) {
            $mimeType = mime_content_type($path);
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000'
            ]);
        }
        abort(404);
    })->name('lesson.images');
    
    // Gestion des quiz
    Route::get('/modules/{moduleId}/quiz/creer', [FormateurController::class, 'createQuiz'])->name('quizzes.create');
    Route::post('/modules/{moduleId}/quiz/creer', [FormateurController::class, 'storeQuiz'])->name('quizzes.store');
    Route::get('/quiz/{quizId}/editer', [FormateurController::class, 'editQuiz'])->name('quizzes.edit');
    Route::put('/quiz/{quizId}/editer', [FormateurController::class, 'updateQuiz'])->name('quizzes.update');
    Route::delete('/quiz/{quizId}', [FormateurController::class, 'destroyQuiz'])->name('quizzes.destroy');
    
    // Gestion des examens finaux
    Route::get('/cours/{courseId}/examen-final/creer', [FormateurController::class, 'createFinalExam'])->name('final-exam.create');
    Route::post('/cours/{courseId}/examen-final/creer', [FormateurController::class, 'storeFinalExam'])->name('final-exam.store');
    Route::get('/cours/{courseId}/examen-final/editer', [FormateurController::class, 'editFinalExam'])->name('final-exam.edit');
    Route::put('/cours/{courseId}/examen-final/editer', [FormateurController::class, 'updateFinalExam'])->name('final-exam.update');
    Route::delete('/cours/{courseId}/examen-final', [FormateurController::class, 'destroyFinalExam'])->name('final-exam.destroy');
    
    // Gestion des étudiants et statistiques
    Route::get('/cours/{courseId}/etudiants', [FormateurController::class, 'courseStudents'])->name('courses.students');
    Route::get('/cours/{courseId}/evaluations', [FormateurController::class, 'courseRatings'])->name('courses.ratings');
    Route::post('/cours/evaluations/{ratingId}/repondre', [FormateurController::class, 'replyToRating'])->name('courses.ratings.reply');
    Route::get('/cours/{courseId}/revenus', [FormateurController::class, 'courseRevenues'])->name('courses.revenues');
    Route::get('/cours/{courseId}/revenus/exporter', [FormateurController::class, 'exportRevenues'])->name('courses.revenues.export');
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
    Route::post('/lesson/{lessonId}/watch-time', [EtudiantController::class, 'updateWatchTime'])->name('apprenant.lesson.watch-time');
    
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

// Public route to serve lesson images (accessible by apprenants and non-formateurs)
Route::get('/lesson-images/{filename}', function ($filename) {
    $path = storage_path('app/public/lesson_images/' . $filename);
    if (file_exists($path)) {
        $mimeType = mime_content_type($path);
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
    abort(404);
})->name('lesson.images');

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
// Forum experts : redirection vers la liste (route nommée pour les liens)
// Route supprimée - gérée par CourseForumController à la ligne 631
// Route::get('/le-forum-des-apprenants', [CoursController::class, 'forumapp'])->name('pages.forumapp');
Route::get('/a-propos-de-AfriCode', [CoursController::class, 'apropos'])->name('pages.apropos');

// Routes de profil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications.update');
});

// Route pour le profil public d'un utilisateur
Route::get('/profil/{id}', [ProfileController::class, 'show'])->name('profile.show');

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

// Routes pour le système de mentorat
Route::middleware(['auth'])->group(function () {
    Route::prefix('mentorship')->name('mentorship.')->group(function () {
        // Routes principales
        Route::get('/', [MentorshipController::class, 'index'])->name('index');
        Route::get('/mentors', [MentorshipController::class, 'mentors'])->name('mentors');
        Route::get('/requests/pending', [MentorshipController::class, 'requests'])->name('requests');
        Route::get('/create', [MentorshipController::class, 'create'])->name('create');
        Route::post('/', [MentorshipController::class, 'store'])->name('store');
        Route::get('/{mentorship}', [MentorshipController::class, 'show'])->name('show');

        // Actions sur les mentorats
        Route::post('/{mentorship}/approve', [MentorshipController::class, 'approve'])->name('approve');
        Route::post('/{mentorship}/decline', [MentorshipController::class, 'decline'])->name('decline');
        Route::post('/{mentorship}/end', [MentorshipController::class, 'end'])->name('end');
    });

    // Routes pour le système de tutorat
    Route::prefix('tutoring')->name('tutoring.')->group(function () {
        // Routes principales
        Route::get('/sessions', [TutoringSessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/create', [TutoringSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [TutoringSessionController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/{session}', [TutoringSessionController::class, 'show'])->name('sessions.show');
        
        // Actions sur les sessions
        Route::post('/sessions/join', [TutoringSessionController::class, 'join'])->name('sessions.join');
        Route::get('/sessions/{session}/room', [TutoringSessionController::class, 'joinRoom'])->name('sessions.join-room');
        Route::post('/sessions/{session}/start', [TutoringSessionController::class, 'start'])->name('sessions.start');
        Route::post('/sessions/{session}/complete', [TutoringSessionController::class, 'complete'])->name('sessions.complete');
        Route::post('/sessions/{session}/cancel', [TutoringSessionController::class, 'cancel'])->name('sessions.cancel');
        
        // Messagerie
        Route::post('/sessions/{session}/message', [TutoringSessionController::class, 'sendMessage'])->name('sessions.send-message');
    });
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

// API IA Assistant (optionnel)
Route::middleware('auth')->prefix('api/ai-assistant')->group(function () {
    Route::post('/chat', [AIAssistantController::class, 'chat'])->name('ai.chat');
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

// Système de votes pour le forum
Route::middleware(['auth'])->prefix('votes')->name('votes.')->group(function () {
    Route::post('/post/{post}', [\App\Http\Controllers\VoteController::class, 'votePost'])->name('post');
    Route::get('/post/{post}/score', [\App\Http\Controllers\VoteController::class, 'getScore'])->name('score');
});

// Route personnalisée pour le forum des apprenants (accès direct)
Route::get('/le-forum-des-apprenants', [\App\Http\Controllers\CourseForumController::class, 'index'])->name('pages.forumapp');

// Liste du forum des experts (URL /le-forum-des-experts)
Route::get('/le-forum-des-experts', function () {
    return redirect()->route('forum.index');
})->name('pages.forumexp');

// Détail d'un sujet du forum des experts (URL /le-forum-des-experts/{id})
Route::get('/le-forum-des-experts/{id}', [\App\Http\Controllers\CourseForumController::class, 'show'])->name('pages.forumexp.show');

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
                    $colors = ['#11c06d', '#fea837', '#fa1c39', '#239e79', '#9B59B6'];
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
        Log::error('Erreur API leaderboard forum: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => 'Erreur serveur'], 500);
    }
})->name('api.forum.leaderboard');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::redirect('/faq', '/support/faq')->name('faq');

Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// Recrutement AfriCode
Route::get('/recrute', [\App\Http\Controllers\RecruitmentController::class, 'index'])->name('recruitment.index');
Route::get('/recrute/{slug}/candidater', [\App\Http\Controllers\RecruitmentController::class, 'form'])->name('recruitment.form');
Route::post('/recrute/{slug}/candidater', [\App\Http\Controllers\RecruitmentController::class, 'storeForm'])->name('recruitment.store');
Route::get('/recrute/{slug}/test/{token}', [\App\Http\Controllers\RecruitmentController::class, 'test'])->name('recruitment.test');
Route::post('/recrute/{slug}/test/{token}', [\App\Http\Controllers\RecruitmentController::class, 'submitTest'])->name('recruitment.submit-test');
Route::get('/recrute/{slug}/resultats/{token}', [\App\Http\Controllers\RecruitmentController::class, 'results'])->name('recruitment.results');
Route::get('/recrute/merci', [\App\Http\Controllers\RecruitmentController::class, 'thankYou'])->name('recruitment.thank-you');

Route::view('/nos-services', 'pages.services')->name('pages.services');
Route::view('/conditions-utilisation', 'pages.terms')->name('pages.terms');
Route::view('/politique-confidentialite', 'pages.privacy')->name('pages.privacy');
Route::view('/confidentialite', 'pages.confidentialite')->name('confidentialite');
Route::view('/parametres-cookies', 'pages.parametres-cookies')->name('parametres.cookies');
Route::post('/parametres-cookies', function(Request $request) {
    $request->validate([
        'preferences' => 'nullable|array',
        'preferences.*' => 'boolean'
    ]);
    
    // Stocker les préférences en session
    session(['cookie_preferences' => $request->input('preferences', [])]);
    
    return redirect()->route('parametres.cookies')->with('success', 'Préférences enregistrées !');
})->name('parametres.cookies.save');

Route::get('/admin/challenges', [App\Http\Controllers\AdminChallengeController::class, 'index'])->name('admin.challenges.index');
Route::get('/admin/challenges/create', [AdminChallengeController::class, 'create'])->name('admin.challenges.create');
Route::post('/admin/challenges', [AdminChallengeController::class, 'store'])->name('admin.challenges.store');
Route::get('/admin/challenges/{challenge}/questions', [App\Http\Controllers\AdminChallengeController::class, 'questions'])->name('admin.challenges.questions');
Route::post('/admin/challenges/{challenge}/questions', [App\Http\Controllers\AdminChallengeController::class, 'storeQuestion'])->name('admin.challenges.questions.store');
Route::delete('/admin/challenges/{challenge}/questions/{question}', [App\Http\Controllers\AdminChallengeController::class, 'destroyQuestion'])->name('admin.challenges.questions.destroy');

Route::get('/competitions/{slug}', [CompetitionDisplayController::class, 'show'])->name('competitions.show');
Route::post('/competitions/{id}/register', [CompetitionDisplayController::class, 'register'])->name('competitions.register');
Route::get('/challenges/{id}', [CompetitionDisplayController::class, 'showChallenge'])->name('challenges.show');
Route::post('/challenges/{id}/participate', [CompetitionDisplayController::class, 'participateChallenge'])->name('challenges.participate');
Route::match(['get', 'post'], '/challenges/{id}/play', [\App\Http\Controllers\CompetitionDisplayController::class, 'playChallenge'])->name('challenges.play');
Route::get('/competitions/{slug}/play', [\App\Http\Controllers\CompetitionDisplayController::class, 'playCompetition'])->name('competitions.play');

// Route pour exécution de code dans une compétition
Route::middleware(['auth'])->post('/competitions/{slug}/run-code', [\App\Http\Controllers\CompetitionController::class, 'runCode'])->name('competitions.runCode');
Route::post('/competitions/{slug}/evaluate', [App\Http\Controllers\CompetitionController::class, 'evaluateSubmission'])->name('competitions.evaluate');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('competitions/{competition}/test-cases', [AdminCompetitionTestCaseController::class, 'index'])->name('admin.competitions.testcases.index');
    Route::get('competitions/{competition}/test-cases/create', [AdminCompetitionTestCaseController::class, 'create'])->name('admin.competitions.testcases.create');
    Route::post('competitions/{competition}/test-cases', [AdminCompetitionTestCaseController::class, 'store'])->name('admin.competitions.testcases.store');
    Route::get('competitions/{competition}/test-cases/{testcase}/edit', [AdminCompetitionTestCaseController::class, 'edit'])->name('admin.competitions.testcases.edit');
    Route::put('competitions/{competition}/test-cases/{testcase}', [AdminCompetitionTestCaseController::class, 'update'])->name('admin.competitions.testcases.update');
    Route::delete('competitions/{competition}/test-cases/{testcase}', [AdminCompetitionTestCaseController::class, 'destroy'])->name('admin.competitions.testcases.destroy');
});

// Routes Push Notifications (VAPID)
Route::middleware(['auth'])->group(function () {
    Route::post('/push-subscription', [\App\Http\Controllers\PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::delete('/push-subscription', [\App\Http\Controllers\PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');
});

// Routes Notifications (cloche — partagées entre tous les rôles)
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/count',    [\App\Http\Controllers\NotificationController::class, 'count'])->name('count');
    Route::post('/read-all',[\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('readAll');
    Route::post('/{id}/read',[\App\Http\Controllers\NotificationController::class, 'markRead'])->name('read');
    // Pages liste par rôle
    Route::get('/apprenant', [\App\Http\Controllers\NotificationController::class, 'apprenantIndex'])->name('apprenant');
    Route::get('/formateur', [\App\Http\Controllers\NotificationController::class, 'formateurIndex'])->name('formateur');
});

// Routes pour le système marketing
Route::prefix('marketing')->group(function () {
    Route::get('promotional-courses', [MarketingController::class, 'getPromotionalCourses'])->name('marketing.promotional-courses');
    Route::get('test-eligibility', [MarketingController::class, 'checkTestEligibility'])->name('marketing.test-eligibility');
    Route::post('mark-test-seen', [MarketingController::class, 'markTestSeen'])->name('marketing.mark-test-seen');
    Route::post('mark-promotion-seen', [MarketingController::class, 'markPromotionSeen'])->name('marketing.mark-promotion-seen');
    Route::get('stats', [MarketingController::class, 'getMarketingStats'])->name('marketing.stats');
});

