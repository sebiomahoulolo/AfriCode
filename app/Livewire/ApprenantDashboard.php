<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Enrollment;
use App\Models\Certification;
use App\Models\LessonCompletion;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

#[Layout('apprenants.layouts.app')]
class ApprenantDashboard extends Component
{
    public $currentView = 'dashboard';
    public $currentLessonId = null;
    public $currentCourseId = null;
    public $showProfile = false;
    public $showCertifications = false;

    protected $listeners = [
        'navigateToLesson' => 'showLesson',
        'navigateToProfile' => 'showProfileView',
        'navigateToDashboard' => 'showDashboard',
        'navigateToCertifications' => 'showCertificationsView',
        'lessonCompleted' => 'refreshDashboard'
    ];

    public function mount()
    {
        // Initialiser la vue par défaut
        $this->currentView = 'dashboard';
    }

    public function showLesson($lessonId)
    {
        $this->currentView = 'lesson';
        $this->currentLessonId = $lessonId;
        
        // Récupérer le cours associé à la leçon
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $this->currentCourseId = $lesson->module->course->id;
    }

    public function showDashboard()
    {
        $this->currentView = 'dashboard';
        $this->currentLessonId = null;
        $this->currentCourseId = null;
    }

    public function showProfileView()
    {
        $this->currentView = 'profile';
    }

    public function showCertificationsView()
    {
        $this->currentView = 'certifications';
    }

    public function refreshDashboard()
    {
        // Cette méthode sera appelée quand une leçon est complétée
        // Pour rafraîchir les données du dashboard
        $this->render();
    }

    public function getDashboardData()
    {
        $user = Auth::user();
        
        // Récupérer les cours auxquels l'apprenant est inscrit avec la progression
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->orderBy('enrolled_at', 'desc')
            ->get();

        // Calculer les statistiques
        $completedCourses = $enrollments->where('completed_at', '!=', null)->count();
        $avgProgress = $enrollments->avg('progress_percentage') ?? 0;

        // Récupérer les certifications obtenues
        $certifications = Certification::where('user_id', $user->id)
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'enrollments' => $enrollments,
            'completedCourses' => $completedCourses,
            'avgProgress' => $avgProgress,
            'certifications' => $certifications,
            'user' => $user
        ];
    }

    public function getCertificationData()
    {
        $user = Auth::user();
        
        $certifications = Certification::where('user_id', $user->id)
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupérer les cours en progression pour la section "Cours en progression"
        $coursesInProgress = Enrollment::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->with('course')
            ->get()
            ->map(function ($enrollment) {
                return (object) [
                    'title' => $enrollment->course->title,
                    'completion_percentage' => $enrollment->progress_percentage
                ];
            });

        return [
            'certifications' => $certifications,
            'courses_in_progress' => $coursesInProgress,
            'courses_completed' => $certifications->count(),
            'total_hours' => $certifications->count() * 10, // Estimation
            'user' => $user
        ];
    }

    public function getProfileData()
    {
        $user = Auth::user();
        
        $enrollments = Enrollment::where('user_id', $user->id)->get();
        $completedCourses = $enrollments->where('completed_at', '!=', null)->count();
        $averageProgress = $enrollments->avg('progress_percentage') ?? 0;
        $certifications = Certification::where('user_id', $user->id)->get();

        return [
            'user' => $user,
            'enrollments' => $enrollments,
            'completedCourses' => $completedCourses,
            'averageProgress' => $averageProgress,
            'certifications' => $certifications
        ];
    }

    public function render()
    {
        // Charger les données selon la vue actuelle
        $data = [];
        
        switch ($this->currentView) {
            case 'dashboard':
                $data = $this->getDashboardData();
                break;
            case 'profile':
                $data = $this->getProfileData();
                break;
            case 'certifications':
                $data = $this->getCertificationData();
                break;
            case 'lesson':
                // Les données de la leçon seront gérées par le composant LessonViewer
                break;
        }

        return view('livewire.apprenant-dashboard', $data);
    }
}
