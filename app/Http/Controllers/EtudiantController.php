<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certification;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        
        // Vérifier si l'apprenant est inscrit au cours
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }
        
        // Trouver la première leçon non complétée
        $completedLessonIds = LessonCompletion::where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->pluck('lesson_id');
            
        // Récupérer tous les modules du cours avec leurs leçons
        $modules = Module::where('course_id', $courseId)
            ->with(['lessons' => function($query) {
                $query->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();
            
        // Trouver la première leçon non complétée
        $nextLesson = null;
        
        foreach ($modules as $module) {
            foreach ($module->lessons as $lesson) {
                if (!$completedLessonIds->contains($lesson->id)) {
                    $nextLesson = $lesson;
                    break 2; // Sort des deux boucles
                }
            }
        }
        
        // Si toutes les leçons sont complétées, prendre la première leçon du cours
        if (!$nextLesson && $modules->isNotEmpty() && $modules->first()->lessons->isNotEmpty()) {
            $nextLesson = $modules->first()->lessons->first();
        }
        
        if ($nextLesson) {
            return redirect()->route('apprenant.lesson', ['lessonId' => $nextLesson->id]);
        }
        
        return redirect()->route('apprenant.dashboard')->with('error', 'Ce cours ne contient pas encore de leçons');
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
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        
        // Vérifier si l'apprenant est inscrit au cours correspondant
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->module->course->id)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }
        
        // Récupérer tous les modules et leçons du cours pour la navigation
        $modules = Module::where('course_id', $lesson->module->course->id)
            ->with(['lessons' => function($query) {
                $query->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();
            
        // Récupérer les leçons complétées par l'apprenant
        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->pluck('lesson_id')
            ->toArray();
            
        // Trouver la leçon suivante pour la navigation
        $nextLesson = null;
        $foundCurrent = false;
        
        foreach ($modules as $module) {
            foreach ($module->lessons as $moduleLesson) {
                if ($foundCurrent) {
                    $nextLesson = $moduleLesson;
                    break 2;
                }
                
                if ($moduleLesson->id === $lesson->id) {
                    $foundCurrent = true;
                }
            }
        }
        
        // Vérifier si cette leçon est déjà marquée comme complétée
        $isCompleted = in_array($lesson->id, $completedLessons);
        
        $resources = $lesson->resources ?? []; // Si vous avez une relation pour les ressources
            
        return view('apprenants.lesson', compact(
            'lesson', 
            'modules', 
            'completedLessons', 
            'nextLesson', 
            'isCompleted',
            'resources'
        ));
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
        
        // Vérifier si l'apprenant est inscrit au cours correspondant
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->module->course->id)
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('apprenant.dashboard')->with('error', 'Vous n\'êtes pas inscrit à ce cours');
        }
        
        // Marquer la leçon comme complétée si elle ne l'est pas déjà
        $completion = LessonCompletion::firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lessonId,
            'enrollment_id' => $enrollment->id
        ], [
            'completed_at' => now()
        ]);
        
        // Mettre à jour la progression globale du cours
        $totalLessons = Lesson::whereHas('module', function($query) use ($lesson) {
            $query->where('course_id', $lesson->module->course->id);
        })->count();
        
        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->count();
            
        $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        
        $enrollment->progress_percentage = $progressPercentage;
        
        // Si toutes les leçons sont complétées
        if ($progressPercentage >= 100) {
            $enrollment->completed_at = now();
            
            // Générer une certification si le cours est terminé
            Certification::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $lesson->module->course->id,
                'enrollment_id' => $enrollment->id
            ], [
                'issued_at' => now(),
                'certificate_path' => '',
                'verification_code' => 'VERIFY-' . uniqid(),
                'certificate_identifier' => 'CERT-' . $user->id . '-' . $lesson->module->course->id . '-' . time()
            ]);
        }
        
        $enrollment->save();
        
        // Rediriger vers la leçon suivante s'il y en a une
        if ($request->has('next_lesson_id') && $request->next_lesson_id) {
            return redirect()->route('apprenant.lesson', ['lessonId' => $request->next_lesson_id]);
        }
        
        return redirect()->route('apprenant.dashboard')->with('success', 'Leçon marquée comme complétée. Cours mis à jour.');
    }
    
    /**
     * Télécharger une certification
     * 
     * @param  int  $certificationId
     * @return \Illuminate\Http\Response
     */
    public function downloadCertification($certificationId)
    {
        $user = Auth::user();
        $certification = Certification::where('id', $certificationId)
            ->where('user_id', $user->id)
            ->with('course')
            ->firstOrFail();
        
        // Ici, vous auriez la logique pour générer un PDF et le télécharger
        // Pour l'instant, on peut rediriger vers une page qui affiche le certificat
        
        return view('apprenants.certification', compact('certification'));
    }
}
