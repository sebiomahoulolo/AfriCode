<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FormateurController extends Controller
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
     * Show the formateur dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les cours créés par le formateur
        $courses = Course::where('formateur_id', $user->id)
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculer les revenus totaux (si applicable)
        $totalRevenue = Payment::where('payable_type', 'App\Models\Course')
            ->whereIn('payable_id', $courses->pluck('id'))
            ->where('status', 'succeeded')
            ->sum('amount');
            
        // Récupérer le nombre total d'inscriptions aux cours du formateur
        $totalEnrollments = Enrollment::whereIn('course_id', $courses->pluck('id'))->count();
        
        // Récupérer la note moyenne des cours du formateur
        $averageRating = Rating::whereIn('course_id', $courses->pluck('id'))->avg('rating') ?? 0;
        
        // Récupérer les activités récentes liées aux cours du formateur
        $recentActivities = collect();
        
        // Récupérer les inscriptions récentes
        $recentEnrollments = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->with(['user', 'course'])
            ->orderBy('enrolled_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($recentEnrollments as $enrollment) {
            $recentActivities->push([
                'type' => 'enrollment',
                'user' => $enrollment->user,
                'course' => $enrollment->course,
                'date' => $enrollment->enrolled_at,
                'message' => 'Nouvel étudiant inscrit à '
            ]);
        }
        
        // Récupérer les notes récentes
        $recentRatings = Rating::whereIn('course_id', $courses->pluck('id'))
            ->with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($recentRatings as $rating) {
            $recentActivities->push([
                'type' => 'rating',
                'user' => $rating->user,
                'course' => $rating->course,
                'rating' => $rating->rating,
                'date' => $rating->created_at,
                'message' => 'Nouvelle évaluation ' . $rating->rating . ' étoiles pour '
            ]);
        }
        
        // Trier toutes les activités par date
        $recentActivities = $recentActivities->sortByDesc('date')->take(5);
        
        return view('formateurs.dashboard', compact(
            'courses', 
            'totalRevenue', 
            'totalEnrollments', 
            'averageRating', 
            'recentActivities'
        ));
    }
    
    /**
     * Afficher la page de gestion d'un cours spécifique
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function manageCourse($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['modules.lessons', 'modules.quizzes'])->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Récupérer les statistiques du cours
        $enrollmentsCount = Enrollment::where('course_id', $courseId)->count();
        $studentsCount = Enrollment::where('course_id', $courseId)->distinct('user_id')->count('user_id');
        $revenue = Payment::where('payable_type', 'App\Models\Course')
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded')
            ->sum('amount');
        $averageRating = Rating::where('course_id', $courseId)->avg('rating') ?? 0;
        $ratingsCount = Rating::where('course_id', $courseId)->count();
        
        return view('formateurs.manage_course', compact(
            'course',
            'enrollmentsCount',
            'studentsCount',
            'revenue',
            'averageRating',
            'ratingsCount'
        ));
    }
    
    /**
     * Afficher le formulaire de création d'un cours
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createCourse()
    {
        $categories = Category::all();
        return view('formateurs.create_course', compact('categories'));
    }
    
    /**
     * Enregistrer un nouveau cours
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'level' => 'required|string|in:débutant,intermédiaire,avancé,expert',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        $slug = Str::slug($request->title);
        $uniqueSlug = $slug;
        $count = 1;
        
        // Assurer l'unicité du slug
        while (Course::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $count++;
        }
        
        // Gérer l'upload de l'image de couverture
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $uniqueSlug . '.' . $image->extension();
            $image->move(public_path('storage/courses'), $imageName);
            $coverImagePath = 'storage/courses/' . $imageName;
        }
        
        // Créer le cours
        $course = Course::create([
            'title' => $request->title,
            'slug' => $uniqueSlug,
            'short_description' => $request->short_description,
            'full_description' => $request->full_description,
            'learning_objectives' => $request->learning_objectives ? json_decode($request->learning_objectives) : [],
            'prerequisites' => $request->prerequisites ? json_decode($request->prerequisites) : [],
            'level' => $request->level,
            'price' => $request->price,
            'currency' => $request->currency,
            'cover_image_path' => $coverImagePath,
            'status' => 'draft',
            'formateur_id' => $user->id,
            'category_id' => $request->category_id,
        ]);
        
        return redirect()->route('formateur.modules.create', ['courseId' => $course->id])
            ->with('success', 'Le cours a été créé avec succès. Vous pouvez maintenant ajouter des modules.');
    }
    
    /**
     * Afficher le formulaire d'édition d'un cours
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editCourse($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce cours');
        }
        
        $categories = Category::all();
        
        return view('formateurs.edit_course', compact('course', 'categories'));
    }
    
    /**
     * Mettre à jour un cours
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCourse(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'level' => 'required|string|in:débutant,intermédiaire,avancé,expert',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce cours');
        }
        
        // Si le titre a changé, mettre à jour le slug
        if ($course->title !== $request->title) {
            $slug = Str::slug($request->title);
            $uniqueSlug = $slug;
            $count = 1;
            
            // Assurer l'unicité du slug
            while (Course::where('slug', $uniqueSlug)->where('id', '!=', $courseId)->exists()) {
                $uniqueSlug = $slug . '-' . $count++;
            }
            
            $course->slug = $uniqueSlug;
        }
        
        // Gérer l'upload de l'image de couverture
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($course->cover_image_path && file_exists(public_path($course->cover_image_path))) {
                unlink(public_path($course->cover_image_path));
            }
            
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $course->slug . '.' . $image->extension();
            $image->move(public_path('storage/courses'), $imageName);
            $course->cover_image_path = 'storage/courses/' . $imageName;
        }
        
        // Mettre à jour le cours
        $course->title = $request->title;
        $course->short_description = $request->short_description;
        $course->full_description = $request->full_description;
        $course->learning_objectives = $request->learning_objectives ? json_decode($request->learning_objectives) : $course->learning_objectives;
        $course->prerequisites = $request->prerequisites ? json_decode($request->prerequisites) : $course->prerequisites;
        $course->level = $request->level;
        $course->price = $request->price;
        $course->currency = $request->currency;
        $course->category_id = $request->category_id;
        $course->save();
        
        return redirect()->route('formateur.manage.course', ['courseId' => $course->id])
            ->with('success', 'Le cours a été mis à jour avec succès.');
    }
    
    /**
     * Afficher la page de création de modules pour un cours
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createModule($courseId)
    {
        $user = Auth::user();
        $course = Course::with('modules')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        return view('formateurs.create_module', compact('course'));
    }
    
    /**
     * Enregistrer un nouveau module
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeModule(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Déterminer l'ordre du nouveau module
        $order = Module::where('course_id', $courseId)->max('order') + 1;
        
        // Créer le module
        $module = Module::create([
            'course_id' => $courseId,
            'title' => $request->title,
            'description' => $request->description,
            'order' => $order,
        ]);
        
        return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
            ->with('success', 'Le module a été ajouté avec succès.');
    }
    
    /**
     * Afficher la page de gestion d'un module
     * 
     * @param  int  $moduleId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function manageModule($moduleId)
    {
        $user = Auth::user();
        $module = Module::with(['course', 'lessons', 'quizzes'])->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        return view('formateurs.manage_module', compact('module'));
    }
    
    /**
     * Afficher le formulaire de création d'une leçon
     * 
     * @param  int  $moduleId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createLesson($moduleId)
    {
        $user = Auth::user();
        $module = Module::with('course')->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        return view('formateurs.create_lesson', compact('module'));
    }
    
    /**
     * Enregistrer une nouvelle leçon
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLesson(Request $request, $moduleId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|string|in:video,text,pdf,external',
            'video_url' => 'nullable|string|required_if:content_type,video',
            'text_content' => 'nullable|string|required_if:content_type,text',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240|required_if:content_type,pdf',
            'external_url' => 'nullable|url|required_if:content_type,external',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_previewable' => 'nullable|boolean',
        ]);
        
        $user = Auth::user();
        $module = Module::with('course')->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        // Déterminer l'ordre de la nouvelle leçon
        $order = Lesson::where('module_id', $moduleId)->max('order') + 1;
        
        // Gérer l'upload du fichier PDF si nécessaire
        $pdfPath = null;
        if ($request->content_type === 'pdf' && $request->hasFile('pdf_file')) {
            $pdf = $request->file('pdf_file');
            $pdfName = time() . '_' . Str::slug($request->title) . '.' . $pdf->extension();
            $pdf->move(public_path('storage/lessons'), $pdfName);
            $pdfPath = 'storage/lessons/' . $pdfName;
        }
        
        // Créer la leçon
        $lesson = Lesson::create([
            'module_id' => $moduleId,
            'title' => $request->title,
            'content_type' => $request->content_type,
            'video_url' => $request->video_url,
            'text_content' => $request->text_content,
            'pdf_path' => $pdfPath,
            'external_url' => $request->external_url,
            'duration_minutes' => $request->duration_minutes,
            'order' => $order,
            'is_previewable' => $request->has('is_previewable'),
        ]);
        
        return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
            ->with('success', 'La leçon a été ajoutée avec succès.');
    }
    
    /**
     * Afficher le formulaire de création d'un quiz
     * 
     * @param  int  $moduleId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createQuiz($moduleId)
    {
        $user = Auth::user();
        $module = Module::with('course')->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        return view('formateurs.create_quiz', compact('module'));
    }
    
    /**
     * Enregistrer un nouveau quiz
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeQuiz(Request $request, $moduleId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required|boolean',
        ]);
        
        $user = Auth::user();
        $module = Module::with('course')->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        DB::beginTransaction();
        
        try {
            // Créer le quiz
            $quiz = Quiz::create([
                'quizzable_type' => 'App\Models\Module',
                'quizzable_id' => $moduleId,
                'title' => $request->title,
                'description' => $request->description,
                'passing_score' => $request->passing_score,
            ]);
            
            // Créer les questions et les réponses
            foreach ($request->questions as $questionData) {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'text' => $questionData['text'],
                    'type' => 'multiple_choice',
                ]);
                
                foreach ($questionData['answers'] as $answerData) {
                    Answer::create([
                        'question_id' => $question->id,
                        'text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct'],
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
                ->with('success', 'Le quiz a été ajouté avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur s\'est produite lors de la création du quiz. ' . $e->getMessage());
        }
    }
    
    /**
     * Publier un cours
     * 
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publishCourse($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['modules.lessons', 'modules.quizzes'])->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à publier ce cours');
        }
        
        // Vérifier que le cours a au moins un module et une leçon
        if ($course->modules->isEmpty()) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Le cours doit avoir au moins un module pour être publié');
        }
        
        $hasLessons = false;
        foreach ($course->modules as $module) {
            if (!$module->lessons->isEmpty() || !$module->quizzes->isEmpty()) {
                $hasLessons = true;
                break;
            }
        }
        
        if (!$hasLessons) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Le cours doit avoir au moins une leçon ou un quiz pour être publié');
        }
        
        // Publier le cours
        $course->status = 'published';
        $course->published_at = now();
        $course->save();
        
        return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
            ->with('success', 'Le cours a été publié avec succès.');
    }
    
    /**
     * Liste des étudiants inscrits à un cours
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courseStudents($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à voir les étudiants de ce cours');
        }
        
        $enrollments = Enrollment::where('course_id', $courseId)
            ->with('user')
            ->orderBy('enrolled_at', 'desc')
            ->paginate(20);
        
        return view('formateurs.course_students', compact('course', 'enrollments'));
    }
    
    /**
     * Liste des évaluations d'un cours
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courseRatings($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à voir les évaluations de ce cours');
        }
        
        $ratings = Rating::where('course_id', $courseId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('formateurs.course_ratings', compact('course', 'ratings'));
    }
    
    /**
     * Liste des revenus générés par un cours
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courseRevenues($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à voir les revenus de ce cours');
        }
        
        // Filtrer par période si nécessaire
        $query = Payment::where('payable_type', 'App\Models\Course')
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded');
            
        if (request('period') === 'month') {
            $query->whereMonth('paid_at', now()->month)
                  ->whereYear('paid_at', now()->year);
        } elseif (request('period') === 'year') {
            $query->whereYear('paid_at', now()->year);
        }
        
        $payments = $query->with('user')
            ->orderBy('paid_at', 'desc')
            ->paginate(15);
        
        $totalRevenue = Payment::where('payable_type', 'App\Models\Course')
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded')
            ->sum('amount');
        
        // Regrouper les paiements par mois pour le graphique
        $monthlyRevenues = Payment::where('payable_type', 'App\Models\Course')
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded')
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->total;
            })
            ->toArray();
        
        return view('formateurs.course_revenues', compact('course', 'payments', 'totalRevenue', 'monthlyRevenues'));
    }
    
    /**
     * Exporter les transactions d'un cours
     * 
     * @param  int  $courseId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportRevenues($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à exporter les revenus de ce cours');
        }
        
        // Récupérer les données
        $payments = Payment::where('payable_type', 'App\Models\Course')
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded')
            ->with('user')
            ->orderBy('paid_at', 'desc')
            ->get();
        
        // Générer le CSV
        $filename = 'revenus_' . Str::slug($course->title) . '_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, ['ID', 'Étudiant', 'Email', 'Date', 'Montant', 'Devise', 'Méthode de paiement', 'ID Transaction']);
            
            // Données
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user->first_name . ' ' . $payment->user->last_name,
                    $payment->user->email,
                    $payment->paid_at->format('d/m/Y H:i:s'),
                    $payment->amount,
                    $payment->currency,
                    $payment->payment_method,
                    $payment->transaction_id,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Répondre à une évaluation
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ratingId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replyToRating(Request $request, $ratingId)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);
        
        $user = Auth::user();
        $rating = Rating::with('course')->findOrFail($ratingId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($rating->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à répondre à cette évaluation');
        }
        
        // Enregistrer la réponse
        $rating->formateur_reply = $request->reply;
        $rating->formateur_reply_at = now();
        $rating->save();
        
        return redirect()->route('formateur.courses.ratings', ['courseId' => $rating->course_id])
            ->with('success', 'Votre réponse a été publiée avec succès.');
    }
}