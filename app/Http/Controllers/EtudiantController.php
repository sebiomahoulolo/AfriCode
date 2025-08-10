<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certification;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserQuizAnswer;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Spatie\Browsershot\Browsershot;

class EtudiantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Le middleware est déjà appliqué dans les routes
    }

    /**
     * Show the apprenant dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les cours auxquels l'apprenant est inscrit avec la progression
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->orderBy('enrolled_at', 'desc')
            ->get();
        
        // Récupérer les certifications obtenues
        $certifications = Certification::where('user_id', $user->id)
            ->with('course')
            ->get();
        
        return view('apprenants.dashboard', compact('enrollments', 'certifications'));
    }
    
    /**
     * Afficher le profil de l'apprenant
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showProfile()
    {
        $user = Auth::user();
        
        // Récupérer les inscriptions avec cours
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course' => function($query) {
                $query->with(['modules.lessons', 'formateur', 'category']);
            }])
            ->orderBy('enrolled_at', 'desc')
            ->get();
        
        // Récupérer les certifications
        $certifications = Certification::where('user_id', $user->id)
            ->with('course')
            ->orderBy('issued_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $totalEnrollments = $enrollments->count();
        $completedCourses = $enrollments->where('completed_at', '!=', null)->count();
        $inProgressCourses = $enrollments->where('completed_at', null)->count();
        $averageProgress = $enrollments->avg('progress_percentage') ?? 0;
        
        // Récupérer les activités récentes (dernières leçons complétées)
        $recentActivities = LessonCompletion::where('user_id', $user->id)
            ->with(['lesson.module.course', 'enrollment'])
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();
        
        // Calculer le temps total d'apprentissage (estimation)
        $totalLearningTime = $enrollments->sum(function($enrollment) {
            return $enrollment->course->modules->sum(function($module) {
                return $module->lessons->sum('duration_minutes');
            });
        });
        
        return view('apprenants.profile-complete', compact(
            'user', 
            'enrollments', 
            'certifications', 
            'totalEnrollments',
            'completedCourses', 
            'inProgressCourses', 
            'averageProgress',
            'recentActivities',
            'totalLearningTime'
        ));
    }
    
    /**
     * Mettre à jour le profil de l'apprenant
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
        }
        
        // Mettre à jour les informations de base
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->city = $request->city;
        $user->country = $request->country;
        
        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Gérer l'upload de l'image de profil
        if ($request->hasFile('profile_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->profile_image_path && file_exists(public_path($user->profile_image_path))) {
                unlink(public_path($user->profile_image_path));
            }
            
            $image = $request->file('profile_image');
            $imageName = 'profile_' . $user->id . '_' . time() . '.' . $image->extension();
            $image->move(public_path('storage/profiles'), $imageName);
            $user->profile_image_path = 'storage/profiles/' . $imageName;
        }
        
        $user->save();
        
        return redirect()->route('apprenant.profile')->with('success', 'Profil mis à jour avec succès');
    }
    
    /**
     * Afficher tous les cours auxquels l'apprenant est inscrit avec leur progression
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myCourses()
    {
        $user = Auth::user();
        
        // Récupérer les cours auxquels l'apprenant est inscrit avec la progression
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course' => function($query) {
                $query->with(['modules.lessons', 'formateur', 'category']);
            }])
            ->orderBy('enrolled_at', 'desc')
            ->paginate(12);
        
        // Récupérer les statistiques générales
        $totalEnrollments = Enrollment::where('user_id', $user->id)->count();
        $completedCourses = Enrollment::where('user_id', $user->id)
            ->where('completed_at', '!=', null)
            ->count();
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('completed_at', null)
            ->count();
        $totalCertifications = Certification::where('user_id', $user->id)->count();
        
        // Calculer la progression moyenne
        $averageProgress = $enrollments->avg('progress_percentage') ?? 0;
        
        return view('apprenants.my-courses', compact(
            'enrollments', 
            'totalEnrollments', 
            'completedCourses', 
            'inProgressCourses', 
            'totalCertifications',
            'averageProgress'
        ));
    }
    
    /**
     * Accéder à un cours spécifique
     * 
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accessCourse($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier si l'apprenant est inscrit au cours
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }
        
        // Utiliser la structure de cours pour trouver la prochaine étape
        $courseStructure = $this->getCourseStructureForUser($course, $user);

        // Parcourir les modules pour trouver la première étape non complétée
        foreach ($courseStructure['modules'] as $moduleData) {
            // Chercher une leçon non complétée
            foreach ($moduleData['lessons'] as $lessonData) {
                if ($lessonData['status'] === 'unlocked' && !$lessonData['is_completed']) {
                    return redirect()->route('apprenant.lesson', ['lessonId' => $lessonData['item']->id]);
                }
            }
            // Si toutes les leçons sont faites, chercher le quiz du module
            if ($moduleData['quiz'] && $moduleData['quiz']['status'] === 'unlocked' && !$moduleData['quiz']['is_completed']) {
                return redirect()->route('apprenant.quiz.show', ['quizId' => $moduleData['quiz']['item']->id]);
            }
        }

        // Si tous les modules sont faits, vérifier le quiz final
        if ($courseStructure['final_quiz'] && $courseStructure['final_quiz']['status'] === 'unlocked' && !$courseStructure['final_quiz']['is_completed']) {
            return redirect()->route('apprenant.quiz.show', ['quizId' => $courseStructure['final_quiz']['item']->id]);
        }

        // Si tout est complété, aller à la première leçon
        if ($course->modules->isNotEmpty() && $course->modules->first()->lessons->isNotEmpty()) {
            return redirect()->route('apprenant.lesson', ['lessonId' => $course->modules->first()->lessons->first()->id]);
        }
        
        return redirect()->route('apprenant.dashboard')->with('info', 'Vous avez terminé ce cours !');
    }
    
    /**
     * Afficher une leçon spécifique
     * 
     * @param  int  $lessonId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showLesson($lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with('module.course.modules')->findOrFail($lessonId);
        $course = $lesson->module->course;
        
        // Vérifier l'inscription
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours.');
        }

        // --- Préparation de la structure du cours pour la sidebar ---
        $courseStructure = $this->getCourseStructureForUser($course, $user);

        // --- Navigation entre leçons ---
        $flatLessons = $course->modules->flatMap(function ($module) {
            return $module->lessons;
        })->sortBy('id');

        $currentLessonIndex = $flatLessons->search(function ($item) use ($lessonId) {
            return $item->id == $lessonId;
        });

        $previousLesson = $currentLessonIndex > 0 ? $flatLessons->get($currentLessonIndex - 1) : null;
        $nextLesson = $currentLessonIndex < $flatLessons->count() - 1 ? $flatLessons->get($currentLessonIndex + 1) : null;

        // Vérifier si la leçon actuelle est complétée
        $isCompleted = $lesson->isCompletedBy($user->id);
        
        // Ressources de la leçon
        $resources = $lesson->resources ?? [];

        // --- Données pour la logique de navigation du pied de page ---
        $moduleLessons = $lesson->module->lessons()->orderBy('order')->get();
        $isLastLessonOfModule = $moduleLessons->isNotEmpty() && $moduleLessons->last()->id === $lesson->id;
        
        $moduleQuiz = $lesson->module->quiz;
        $moduleQuizPassed = $moduleQuiz ? $moduleQuiz->isPassedByUser($user->id) : true;

        $firstLessonNextModule = null;
        $nextModule = $course->modules()->where('order', '>', $lesson->module->order)->orderBy('order')->first();
        if ($nextModule) {
            $firstLessonNextModule = $nextModule->lessons()->orderBy('order')->first();
        }
        // --- Fin des données pour le pied de page ---

        // Récupérer la certification si elle existe
        $certification = null;
        if ($course->is_certifying) {
            $certification = Certification::where('user_id', $user->id)
                ->where('course_id', $course->id)
            ->first();
        }

        return view('apprenants.lesson', compact(
            'lesson',
            'course',
            'courseStructure',
            'previousLesson',
            'nextLesson',
            'isCompleted',
            'resources',
            'isLastLessonOfModule',
            'moduleQuiz',
            'moduleQuizPassed',
            'firstLessonNextModule',
            'certification'
        ));
    }

    /**
     * Construit la structure complète du cours pour un utilisateur donné,
     * incluant le statut de complétion et d'accès pour chaque leçon et quiz.
     */
    private function getCourseStructureForUser(Course $course, User $user)
    {
        $course->load([
            'modules' => fn($q) => $q->orderBy('order'),
            'modules.lessons' => fn($q) => $q->orderBy('order'),
            'modules.quiz',
            'finalQuiz'
        ]);

        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->pluck('lesson_id');

        $structure = [
            'modules' => [],
            'final_quiz' => null,
        ];

        $previousModuleCompleted = true;

        foreach ($course->modules as $module) {
            $lessonsData = [];
            $allLessonsInModuleCompleted = true;

            foreach ($module->lessons as $lesson) {
                $isCompleted = $completedLessons->contains($lesson->id);
                if (!$isCompleted) {
                    $allLessonsInModuleCompleted = false;
                }
                $lessonsData[] = [
                    'item' => $lesson,
                    'type' => 'lesson',
                    'status' => $previousModuleCompleted ? 'unlocked' : 'locked',
                    'is_completed' => $isCompleted,
                ];
            }

            $moduleQuizData = null;
            if ($module->quiz) {
                $quizPassed = $module->quiz->isPassedByUser($user->id);
                $quizLocked = !$previousModuleCompleted || !$allLessonsInModuleCompleted;
                
                $moduleQuizData = [
                    'item' => $module->quiz,
                    'type' => 'quiz',
                    'status' => $quizLocked ? 'locked' : 'unlocked',
                    'is_completed' => $quizPassed,
                ];
            }
            
            $structure['modules'][] = [
                'module' => $module,
                'lessons' => $lessonsData,
                'quiz' => $moduleQuizData,
            ];

            // Pour le module suivant, il faut que le quiz du module actuel soit passé (s'il existe)
            $previousModuleCompleted = $allLessonsInModuleCompleted && (!$module->quiz || $module->quiz->isPassedByUser($user->id));
        }

        if ($course->finalQuiz) {
             $finalQuizPassed = $course->finalQuiz->isPassedByUser($user->id);
             $finalQuizLocked = !$previousModuleCompleted;

             $structure['final_quiz'] = [
                'item' => $course->finalQuiz,
                'type' => 'quiz',
                'status' => $finalQuizLocked ? 'locked' : 'unlocked',
                'is_completed' => $finalQuizPassed,
             ];
        }

        // Calculer les statistiques de progression
        $totalLessons = $course->modules->flatMap->lessons->count();
        $completedLessonsCount = $completedLessons->count();
        $progressPercentage = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;

        $structure['stats'] = [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessonsCount,
            'progress_percentage' => $progressPercentage,
        ];

        return $structure;
    }
    
    /**
     * Marquer une leçon comme complétée
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeLesson(Request $request, $lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        // Marquer la leçon comme complétée si elle ne l'est pas déjà
        LessonCompletion::firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lessonId,
            'enrollment_id' => $enrollment->id
        ], ['completed_at' => now()]);
        
        // Mettre à jour la progression du cours
        $this->updateCourseProgress($enrollment);
        
        // Vérifier si le cours entier est maintenant terminé
        if ($course->isCompletedByUser($user->id)) {
            $this->completeEnrollmentAndGenerateCertificate($enrollment);
        }
        
        // Déterminer la prochaine étape pour l'utilisateur
        $nextUrl = $this->getNextStepUrl($lesson);

        return redirect($nextUrl)->with('success', 'Leçon marquée comme terminée !');
    }

    private function updateCourseProgress(Enrollment $enrollment)
    {
        $course = $enrollment->course;
        $totalLessons = $course->getLessonsCount();
        
        if ($totalLessons > 0) {
            $completedLessons = LessonCompletion::where('enrollment_id', $enrollment->id)->count();
            $enrollment->progress_percentage = ($completedLessons / $totalLessons) * 100;
        } else {
            $enrollment->progress_percentage = 100;
        }

        $enrollment->save();
    }

    private function completeEnrollmentAndGenerateCertificate(Enrollment $enrollment)
    {
        // Marquer l'inscription comme terminée si ce n'est pas déjà fait
        if (!$enrollment->completed_at) {
            $enrollment->completed_at = now();
            $enrollment->save();
        }
            
        // On ne génère un certificat que si le cours est certifiant (is_certifying = 1)
        if ($enrollment->course->is_certifying == 1) {
            $finalQuiz = $enrollment->course->finalQuiz;
            
            // Vérifier si le quiz final existe et est réussi
            $finalQuizPassed = $finalQuiz && $finalQuiz->isPassedByUser($enrollment->user_id);
            
            // Vérifier tous les quiz requis
            $allRequiredQuizzesPassed = true;
            foreach ($enrollment->course->modules as $module) {
                if ($module->quiz && $module->quiz->is_required == 1 && !$module->quiz->isPassedByUser($enrollment->user_id)) {
                    $allRequiredQuizzesPassed = false;
                    break;
                }
            }

            // Si toutes les conditions sont remplies, créer ou récupérer la certification
            if ($finalQuizPassed && $allRequiredQuizzesPassed) {
                $certification = Certification::firstOrCreate(
                    [
                        'user_id' => $enrollment->user_id,
                        'course_id' => $enrollment->course_id,
                    ],
                    [
                        'enrollment_id' => $enrollment->id,
                    'issued_at' => now(),
                    'certificate_path' => '',
                    'verification_code' => 'VERIFY-' . uniqid(),
                        'certificate_identifier' => 'CERT-' . $enrollment->user_id . '-' . $enrollment->course_id . '-' . time()
                    ]
                );

                return $certification;
            }
        }

        return null;
    }

    private function getNextStepUrl(Lesson $lesson)
    {
        $user = auth()->user();
        $module = $lesson->module;

        // If all lessons in the module are done, go to the module quiz
        if ($module->allLessonsCompletedByUser($user->id)) {
            if ($module->quiz) {
                return route('apprenant.quiz.show', ['quizId' => $module->quiz->id]);
            }
        }

        // Otherwise, go to the next lesson
        $nextLesson = $this->findNextLesson($lesson);
        if ($nextLesson) {
            return route('apprenant.lesson', ['lessonId' => $nextLesson->id]);
        }

        // If no next lesson, go to the final quiz
        if ($lesson->module->course->finalQuiz) {
            return route('apprenant.quiz.show', ['quizId' => $lesson->module->course->finalQuiz->id]);
        }
        
        // If nothing else, go to dashboard
        return route('apprenant.dashboard');
    }

    private function findNextLesson(Lesson $currentLesson)
    {
        $course = $currentLesson->module->course;
        $allLessons = $course->modules()->orderBy('order')->with(['lessons' => fn ($q) => $q->orderBy('order')])->get()->flatMap->lessons;
        
        $currentLessonIndex = $allLessons->search(fn($l) => $l->id === $currentLesson->id);

        if ($currentLessonIndex !== false && isset($allLessons[$currentLessonIndex + 1])) {
            return $allLessons[$currentLessonIndex + 1];
        }

        return null;
    }
    
    /**
     * Afficher la page des certifications de l'apprenant
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showCertifications()
    {
        $user = Auth::user();
        
        // Récupérer toutes les certifications de l'utilisateur
        $certifications = Certification::where('user_id', $user->id)
            ->with(['course' => function($query) {
                $query->with(['formateur', 'category']);
            }])
            ->orderBy('issued_at', 'desc')
            ->get();
        
        // Récupérer les cours en progression (pour inciter à terminer)
        $coursesInProgress = Enrollment::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->where('progress_percentage', '>', 0)
            ->with(['course' => function($query) {
                $query->with(['formateur', 'category']);
            }])
            ->orderBy('progress_percentage', 'desc')
            ->get();
        
        // Statistiques des certifications
        $stats = [
            'total_certifications' => $certifications->count(),
            'this_year_certifications' => $certifications->filter(function($cert) {
                return $cert->issued_at->year === now()->year;
            })->count(),
            'total_courses_completed' => Enrollment::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->count(),
            'average_completion_time' => $this->calculateAverageCompletionTime($user->id)
        ];
        
        return view('apprenants.certifications', compact(
            'certifications', 
            'coursesInProgress', 
            'stats'
        ));
    }
    
    /**
     * Afficher un quiz
     * 
     * @param  int  $quizId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showQuiz($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['questions', 'module.course', 'course'])->findOrFail($quizId);

        $course = $quiz->course ?? $quiz->module->course;
        $module = $quiz->module; // Peut être null pour un quiz de cours
        
        // Vérifier l'inscription au cours
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }
        
        // Vérifier si l'utilisateur peut accéder à ce quiz
        if (!$quiz->canBeAccessedByUser($user->id)) {
            $message = $quiz->isModuleQuiz() 
                ? 'Vous devez terminer toutes les leçons de ce module avant d\'accéder au quiz'
                : 'Vous devez réussir tous les quiz des modules avant d\'accéder au quiz final';
                
            return redirect()->back()->with('error', $message);
        }
        
        // Récupérer les tentatives et le statut
        $attempts = $quiz->attempts()->where('user_id', $user->id)->latest()->get();
        $latestAttempt = $attempts->first();
        $bestAttempt = $quiz->bestAttemptByUser($user->id);
        $passed = $quiz->isPassedByUser($user->id);
        $canAttempt = $quiz->canBeAttemptedByUser($user->id);
        $remainingAttempts = $quiz->getRemainingAttempts($user->id);
        
        // Le formulaire pour prendre le quiz est dans une autre vue, ici on affiche la page d'introduction
        return view('apprenants.quiz.show', compact(
            'quiz', 
            'enrollment',
            'module',
            'course',
            'attempts',
            'latestAttempt',
            'bestAttempt',
            'passed',
            'canAttempt',
            'remainingAttempts'
        ));
    }
    
    /**
     * Affiche la page pour passer un quiz
     *
     * @param int $quizId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function startQuiz($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['questions.answers', 'module.course', 'course'])->findOrFail($quizId);
        $course = $quiz->course ?? $quiz->module->course;

        // Vérifier l'inscription
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }

        // Vérifier si l'utilisateur peut accéder à ce quiz
        if (!$quiz->canBeAccessedByUser($user->id)) {
            return redirect()->route('apprenant.quiz.show', ['quizId' => $quiz->id])
                             ->with('error', 'Vous devez d\'abord terminer les étapes précédentes.');
        }

        // Vérifier si l'utilisateur peut encore tenter le quiz
        if (!$quiz->canBeAttemptedByUser($user->id)) {
            $message = $quiz->isPassedByUser($user->id) 
                ? 'Vous avez déjà réussi ce quiz. Vous ne pouvez plus le retenter.'
                : 'Vous avez atteint le nombre maximum de tentatives pour ce quiz.';
            return redirect()->route('apprenant.quiz.show', ['quizId' => $quiz->id])
                             ->with('error', $message);
        }
        
        return view('apprenants.quiz.take', compact('quiz', 'enrollment'));
    }
    
    /**
     * Soumettre un quiz
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $quizId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function takeQuiz(Request $request, $quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['questions.answers'])->findOrFail($quizId);
        
        // Vérifier l'inscription au cours
        $courseId = $quiz->course_id ?? $quiz->module->course_id;
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }
        
        // Créer une nouvelle tentative
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'enrollment_id' => $enrollment->id,
            'started_at' => now(),
            'status' => 'completed'
        ]);
        
        $totalPoints = 0;
        $earnedPoints = 0;
        $correctAnswersCount = 0;
        
        // Traiter les réponses
        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $userAnswerIds = $request->input("question_{$question->id}", []);
            
            if (!is_array($userAnswerIds)) {
                $userAnswerIds = [$userAnswerIds];
            }
            
            $isCorrect = false;
            
            if ($question->type === 'single_choice') {
                $correctAnswer = $question->answers->where('is_correct', true)->first();
                $isCorrect = $correctAnswer && in_array($correctAnswer->id, $userAnswerIds);
            } elseif ($question->type === 'multiple_choice') {
                $correctAnswers = $question->answers->where('is_correct', true)->pluck('id')->toArray();
                $isCorrect = count($correctAnswers) === count($userAnswerIds) && 
                           empty(array_diff($correctAnswers, $userAnswerIds));
            } elseif ($question->type === 'true_false') {
                $correctAnswer = $question->answers->where('is_correct', true)->first();
                $isCorrect = $correctAnswer && in_array($correctAnswer->id, $userAnswerIds);
            }
            
            if ($isCorrect) {
                $earnedPoints += $question->points;
                $correctAnswersCount++;
            }
            
            // Enregistrer les réponses utilisateur
            foreach ($userAnswerIds as $answerId) {
                if ($answerId) {
                    UserQuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'answer_id' => $answerId,
                        'is_correct' => $isCorrect
                    ]);
                }
            }
        }
        
        // Calculer le score
        $scorePercentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
        $passed = $scorePercentage >= $quiz->passing_score;
        
        // Mettre à jour la tentative
        $attempt->update([
            'completed_at' => now(),
            'score' => $scorePercentage,
            'passed' => $passed
        ]);
        
        // Si le quiz est réussi, vérifier si le cours est terminé pour générer le certificat
        $certification = null;
        if ($passed) {
            $course = $quiz->course ?? $quiz->module->course;
            if ($course && $course->isCompletedByUser($user->id)) {
                $certification = $this->completeEnrollmentAndGenerateCertificate($enrollment);
                
                // Si c'est un quiz final réussi et qu'un certificat a été généré
                if ($certification && $quiz->isCourseQuiz() && $course->is_certifying == 1) {
                    return redirect()->route('apprenant.certification.download', ['certificationId' => $certification->id])
                        ->with('success', 'Félicitations ! Vous avez obtenu votre certificat.');
                }
            }
        }
        
        return redirect()->route('apprenant.quiz.result', [
            'quizId' => $quiz->id,
            'attemptId' => $attempt->id
        ]);
    }
    
    /**
     * Afficher les résultats d'un quiz
     * 
     * @param  int  $quizId
     * @param  int  $attemptId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showQuizResult($quizId, $attemptId)
    {
        $user = Auth::user();
        
        // Chargement séparé pour éviter les conflits
        $quiz = Quiz::with(['questions.answers'])->findOrFail($quizId);
        
        // S'assurer que les relations sont bien chargées
        if (!$quiz->relationLoaded('questions')) {
            $quiz->load('questions.answers');
        }
        
        // Charger les relations supplémentaires si nécessaire
        if ($quiz->module_id) {
            $quiz->load('module.course');
        }
        if ($quiz->course_id) {
            $quiz->load('course');
        }
        
        // Charger la tentative avec ses relations
        $attempt = QuizAttempt::with(['answers.answer', 'answers.question'])
            ->where('id', $attemptId)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Vérification de sécurité supplémentaire
        if (!$attempt) {
            return redirect()->route('apprenant.dashboard')
                ->with('error', 'Tentative de quiz introuvable.');
        }
            
        // Statistiques pour la vue
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;
        
        // Vérification de sécurité pour éviter l'erreur sur null
        if ($attempt->answers && $attempt->answers->count() > 0) {
            $correctAnswers = $attempt->answers()->where('is_correct', true)->distinct('question_id')->count();
        }
        
        // Utilisation directe de QuizAttempt au lieu de la relation
        $userAttempts = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $user->id)->count();

        // Grouper les réponses par question pour l'affichage détaillé
        $questionResults = [];
        
        // Pré-charger toutes les réponses correctes pour éviter les requêtes répétées
        $questionIds = $quiz->questions->pluck('id');
        $allCorrectAnswers = Answer::whereIn('question_id', $questionIds)
            ->where('is_correct', true)
            ->get()
            ->groupBy('question_id');
        
        foreach ($quiz->questions as $question) {
            // S'assurer que $attempt->answers est une collection et non null
            $userAnswers = collect();
            if ($attempt->answers && $attempt->answers->count() > 0) {
                $userAnswers = $attempt->answers->where('question_id', $question->id);
            }
            
            $correctAnswersForQuestion = $allCorrectAnswers->get($question->id, collect());
            
            $questionResults[] = [
                'question' => $question,
                'user_answers' => $userAnswers,
                'correct_answers' => $correctAnswersForQuestion,
                'is_correct' => $userAnswers->isNotEmpty() && $userAnswers->first()->is_correct
            ];
        }
        
        $passed = $attempt->score >= $quiz->passing_score;
        
        // Initialiser la certification à null par défaut
        $certification = null;
        
        // Déterminer la navigation de retour en fonction du type de quiz et du résultat
        if ($quiz->isModuleQuiz() && $passed) {
            // Si c'est un quiz de module réussi, on redirige vers la première leçon du module suivant
            $nextModule = $quiz->module->course->modules()
                ->where('order', '>', $quiz->module->order)
                ->orderBy('order')
                ->first();

            if ($nextModule && $nextModule->lessons->isNotEmpty()) {
                $backRoute = 'apprenant.lesson';
                $backId = $nextModule->lessons->first()->id;
            } else {
                // S'il n'y a pas de module suivant, on va au quiz final s'il existe
                $finalQuiz = $quiz->module->course->finalQuiz;
                if ($finalQuiz) {
                    $backRoute = 'apprenant.quiz.show';
                    $backId = $finalQuiz->id;
                } else {
                    $backRoute = 'apprenant.course.access';
                    $backId = $quiz->module->course_id;
                }
            }
        } elseif ($quiz->isCourseQuiz() && $passed) {
            // Si c'est un quiz final réussi, on vérifie la certification
            if ($quiz->course && $quiz->course->is_certifying) {
                $certification = Certification::where('user_id', $user->id)
                    ->where('course_id', $quiz->course->id)
                    ->first();
                
                if ($certification) {
                    $backRoute = 'apprenant.certification.download';
                    $backId = $certification->id;
                } else {
                    $backRoute = 'apprenant.course.access';
                    $backId = $quiz->course_id;
                }
            } else {
                $backRoute = 'apprenant.course.access';
                $backId = $quiz->course_id;
            }
        } else {
            // Si le quiz n'est pas réussi ou autre cas
        $backRoute = $quiz->isModuleQuiz() 
            ? 'apprenant.lesson' 
            : 'apprenant.course.access';
            
        $backId = $quiz->isModuleQuiz() 
                ? ($quiz->module->lessons->first()->id ?? null)
            : $quiz->course_id;
        }
        
        return view('apprenants.quiz.result', compact(
            'quiz', 
            'attempt', 
            'questionResults',
            'backRoute',
            'backId',
            'totalQuestions',
            'correctAnswers',
            'userAttempts',
            'certification',
            'passed',
            'user'
        ));
    }

    /**
     * Calculer le temps moyen de complétion des cours
     */
    private function calculateAverageCompletionTime($userId)
    {
        $completedEnrollments = Enrollment::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->get();
        
        if ($completedEnrollments->isEmpty()) {
            return 0;
        }
        
        $totalDays = $completedEnrollments->sum(function($enrollment) {
            return $enrollment->enrolled_at->diffInDays($enrollment->completed_at);
        });
        
        return round($totalDays / $completedEnrollments->count());
    }

    /**
     * Vérifier que tous les quiz obligatoires d'un cours sont réussis pour générer le certificat
     * 
     * @param  int  $userId
     * @param  int  $courseId
     * @return bool
     */
    private function checkQuizRequirementsForCertification($userId, $courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
                return false;
            }
        return $course->isCompletedByUser($userId);
    }

    /**
     * Afficher et télécharger un certificat
     * 
     * @param  int  $certificationId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function downloadCertification($certificationId)
    {
        $user = Auth::user();
        $certification = Certification::with(['user', 'course'])
            ->where('id', $certificationId)
            ->firstOrFail();
            
        // Vérifier que l'utilisateur a le droit d'accéder à ce certificat
        if ($certification->user_id !== $user->id) {
            return redirect()->route('apprenant.dashboard')
                ->with('error', 'Vous n\'avez pas accès à ce certificat.');
        }
        
        return view('apprenants.certification', compact('certification'));
    }
}
