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
use Illuminate\Support\Facades\Log;

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
        $totalRevenue = Payment::where('payable_type', \App\Models\Course::class)
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
        $course = Course::with(['modules.lessons', 'modules.quiz'])->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Récupérer les statistiques du cours
        $enrollmentsCount = Enrollment::where('course_id', $courseId)->count();
        $studentsCount = Enrollment::where('course_id', $courseId)->distinct('user_id')->count('user_id');
        $revenue = Payment::where('payable_type', \App\Models\Course::class)
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
            'is_certifying' => 'nullable|boolean',
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
            'is_certifying' => $request->has('is_certifying'),
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
            'is_certifying' => 'nullable|boolean',
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
        $course->is_certifying = $request->has('is_certifying');
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
        $module = Module::with(['course', 'lessons', 'quiz.questions'])->findOrFail($moduleId);
        
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
        $course = $module->course;
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        return view('formateurs.create_lesson', compact('module', 'course'));
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
            'is_previewable' => 'nullable|in:on,1,true',
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
        $module = Module::with(['course', 'quiz'])->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        // Vérifier qu'il n'y a pas déjà un quiz pour ce module
        if ($module->quiz) {
            return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
                ->with('error', 'Ce module a déjà un quiz. Vous pouvez le modifier ou le supprimer.');
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
        
        // Validation supplémentaire : s'assurer qu'au moins une réponse est correcte par question
        foreach ($request->questions as $index => $question) {
            $hasCorrectAnswer = collect($question['answers'])->contains('is_correct', true);
            if (!$hasCorrectAnswer) {
                return back()->withErrors([
                    "questions.{$index}" => "La question " . ($index + 1) . " doit avoir au moins une réponse correcte."
                ])->withInput();
            }
        }
        
        $user = Auth::user();
        $module = Module::with(['course', 'quiz'])->findOrFail($moduleId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        // Vérifier qu'il n'y a pas déjà un quiz pour ce module
        if ($module->quiz) {
            return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
                ->with('error', 'Ce module a déjà un quiz. Vous pouvez le modifier ou le supprimer.');
        }
        
        DB::beginTransaction();
        
        try {
            // Créer le quiz
            $quiz = Quiz::create([
                'module_id' => $moduleId,
                'quiz_type' => 'module_end',
                'title' => $request->title,
                'description' => $request->description,
                'passing_score' => $request->passing_score,
                'is_required' => true, // Les quiz de module sont obligatoires par défaut
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
        $course = Course::with(['modules.lessons', 'modules.quiz'])->findOrFail($courseId);
        
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
            if (!$module->lessons->isEmpty() || $module->quiz) {
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
        $query = Payment::where('payable_type', \App\Models\Course::class)
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
        
        // $totalRevenue = Payment::where('course_id', $courseId)
        $totalRevenue = Payment::where('payable_type', \App\Models\Course::class)
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded')
            ->sum('amount');
        
        // Regrouper les paiements par mois pour le graphique
        // $monthlyRevenues = Payment::where('course_id', $courseId)
        $monthlyRevenues = Payment::where('payable_type', \App\Models\Course::class)
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
        // $payments = Payment::where('course_id', $courseId)
        $payments = Payment::where('payable_type', \App\Models\Course::class)
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

    /**
     * Supprimer un cours.
     *
     * @param  int  $courseId
     * @return \\Illuminate\\Http\\RedirectResponse
     */
    public function destroyCourse($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['modules.lessons', 'modules.quizzes.questions.answers', 'enrollments', 'ratings'])->findOrFail($courseId);

        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à supprimer ce cours.');
        }

        DB::beginTransaction();
        try {
            // 1. Supprimer l'image de couverture du cours
            if ($course->cover_image_path && Storage::disk('public')->exists(str_replace('storage/', '', $course->cover_image_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $course->cover_image_path));
            }

            // 2. Supprimer les modules, leçons, quiz, questions, réponses
            foreach ($course->modules as $module) {
                // Supprimer les leçons et leurs fichiers PDF associés
                foreach ($module->lessons as $lesson) {
                    if ($lesson->content_type === 'pdf' && $lesson->pdf_path && Storage::disk('public')->exists(str_replace('storage/', '', $lesson->pdf_path))) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $lesson->pdf_path));
                    }
                    $lesson->delete();
                }

                // Supprimer les quiz, questions et réponses
                foreach ($module->quizzes as $quiz) {
                    foreach ($quiz->questions as $question) {
                        $question->answers()->delete(); // Supprime les réponses associées à la question
                    }
                    $quiz->questions()->delete(); // Supprime les questions associées au quiz
                    $quiz->delete(); // Supprime le quiz
                }
                $module->delete(); // Supprime le module
            }

            // 3. Supprimer les inscriptions
            $course->enrollments()->delete();

            // 4. Supprimer les évaluations
            $course->ratings()->delete();

            // 5. Supprimer les paiements liés au cours
            Payment::where('payable_type', \App\Models\Course::class)
                   ->where('payable_id', $course->id)
                   ->delete();

            // 6. Supprimer le cours lui-même
            $course->delete();

            DB::commit();

            return redirect()->route('formateur.dashboard')->with('success', 'Le cours et toutes ses données associées ont été supprimés avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression du cours {$courseId}: " . $e->getMessage());
            return redirect()->route('formateur.dashboard')->with('error', 'Une erreur s\'est produite lors de la suppression du cours. Veuillez réessayer.');
        }
    }
    
    /**
     * Afficher les détails d'une leçon pour édition
     * 
     * @param  int  $lessonId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editLesson($lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with(['module.course'])->findOrFail($lessonId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($lesson->module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier cette leçon');
        }
        
        return view('formateurs.edit_lesson', compact('lesson'));
    }
    
    /**
     * Mettre à jour une leçon existante
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLesson(Request $request, $lessonId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|string|in:video,text,pdf,external',
            'video_url' => 'nullable|string|required_if:content_type,video',
            'text_content' => 'nullable|string|required_if:content_type,text',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'external_url' => 'nullable|url|required_if:content_type,external',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_previewable' => 'nullable|boolean',
        ]);
        
        $user = Auth::user();
        $lesson = Lesson::with(['module.course'])->findOrFail($lessonId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($lesson->module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier cette leçon');
        }
        
        // Gérer l'upload du fichier PDF si nécessaire
        if ($request->content_type === 'pdf' && $request->hasFile('pdf_file')) {
            // Supprimer l'ancien fichier PDF si existant
            if ($lesson->pdf_path && Storage::disk('public')->exists(str_replace('storage/', '', $lesson->pdf_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $lesson->pdf_path));
            }
            
            $pdf = $request->file('pdf_file');
            $pdfName = time() . '_' . Str::slug($request->title) . '.' . $pdf->extension();
            $pdf->move(public_path('storage/lessons'), $pdfName);
            $lesson->pdf_path = 'storage/lessons/' . $pdfName;
        }
        
        // Mettre à jour les informations de la leçon
        $lesson->title = $request->title;
        
        // Ne mettre à jour le contenu que si le type ne change pas ou selon le nouveau type
        if ($lesson->content_type === $request->content_type || $request->content_type === 'video') {
            $lesson->video_url = $request->video_url;
        }
        
        if ($lesson->content_type === $request->content_type || $request->content_type === 'text') {
            $lesson->text_content = $request->text_content;
        }
        
        if ($lesson->content_type === $request->content_type || $request->content_type === 'external') {
            $lesson->external_url = $request->external_url;
        }
        
        $lesson->content_type = $request->content_type;
        $lesson->duration_minutes = $request->duration_minutes;
        $lesson->is_previewable = $request->has('is_previewable');
        $lesson->save();
        
        return redirect()->route('formateur.manage.module', ['moduleId' => $lesson->module_id])
            ->with('success', 'La leçon a été mise à jour avec succès.');
    }
    
    /**
     * Supprimer une leçon
     * 
     * @param  int  $lessonId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyLesson($lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with(['module.course'])->findOrFail($lessonId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($lesson->module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette leçon');
        }
        
        $moduleId = $lesson->module_id;
        
        // Supprimer le fichier PDF associé si existant
        if ($lesson->content_type === 'pdf' && $lesson->pdf_path && Storage::disk('public')->exists(str_replace('storage/', '', $lesson->pdf_path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $lesson->pdf_path));
        }
        
        $lesson->delete();
        
        // Réorganiser l'ordre des leçons restantes
        $remainingLessons = Lesson::where('module_id', $moduleId)
            ->orderBy('order')
            ->get();
            
        foreach ($remainingLessons as $index => $remainingLesson) {
            $remainingLesson->order = $index + 1;
            $remainingLesson->save();
        }
        
        return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
            ->with('success', 'La leçon a été supprimée avec succès.');
    }
    
    /**
     * Afficher les détails d'un quiz pour édition
     * 
     * @param  int  $quizId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editQuiz($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['questions.answers', 'module.course'])->findOrFail($quizId);
        
        // Vérifier que le quiz est bien associé à un module et que le formateur est propriétaire du cours
        if (!$quiz->module_id) {
            return redirect()->route('formateur.dashboard')->with('error', 'Ce quiz n\'est pas associé à un module');
        }
        
        $module = $quiz->module;
        
        if (!$module || ($module->course->formateur_id !== $user->id && $user->role !== 'admin')) {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce quiz');
        }
        
        return view('formateurs.edit_quiz', compact('quiz', 'module'));
    }
    
    /**
     * Mettre à jour un quiz existant
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $quizId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQuiz(Request $request, $quizId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:questions,id',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.id' => 'nullable|exists:answers,id',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required|boolean',
        ]);
        
        // Validation supplémentaire : s'assurer qu'au moins une réponse est correcte par question
        foreach ($request->questions as $index => $question) {
            $hasCorrectAnswer = collect($question['answers'])->contains('is_correct', true);
            if (!$hasCorrectAnswer) {
                return back()->withErrors([
                    "questions.{$index}" => "La question " . ($index + 1) . " doit avoir au moins une réponse correcte."
                ])->withInput();
            }
        }
        
        $user = Auth::user();
        $quiz = Quiz::with(['questions.answers', 'module.course'])->findOrFail($quizId);
        
        // Vérifier que le quiz est bien associé à un module et que le formateur est propriétaire du cours
        if (!$quiz->module_id) {
            return redirect()->route('formateur.dashboard')->with('error', 'Ce quiz n\'est pas associé à un module');
        }
        
        $module = $quiz->module;
        
        if (!$module || ($module->course->formateur_id !== $user->id && $user->role !== 'admin')) {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce quiz');
        }
        
        // Mise à jour en transaction pour garantir la cohérence
        DB::beginTransaction();
        
        try {
            // Mettre à jour les informations du quiz
            $quiz->title = $request->title;
            $quiz->description = $request->description;
            $quiz->passing_score = $request->passing_score;
            $quiz->save();
            
            // Liste des IDs de questions à conserver
            $questionIds = collect($request->questions)
                ->filter(function ($q) {
                    return !empty($q['id']);
                })
                ->pluck('id')
                ->toArray();
                
            // Supprimer les questions qui ne sont plus dans la liste
            foreach ($quiz->questions as $question) {
                if (!in_array($question->id, $questionIds)) {
                    // Supprimer d'abord les réponses
                    $question->answers()->delete();
                    // Puis la question
                    $question->delete();
                }
            }
            
            // Mettre à jour ou créer les questions
            foreach ($request->questions as $index => $questionData) {
                if (!empty($questionData['id'])) {
                    // Mise à jour d'une question existante
                    $question = Question::find($questionData['id']);
                    $question->text = $questionData['text'];
                    $question->order = $index + 1;
                    $question->save();
                    
                    // Liste des IDs de réponses à conserver pour cette question
                    $answerIds = collect($questionData['answers'])
                        ->filter(function ($a) {
                            return !empty($a['id']);
                        })
                        ->pluck('id')
                        ->toArray();
                    
                    // Supprimer les réponses qui ne sont plus dans la liste
                    foreach ($question->answers as $answer) {
                        if (!in_array($answer->id, $answerIds)) {
                            $answer->delete();
                        }
                    }
                    
                    // Mettre à jour ou créer les réponses
                    foreach ($questionData['answers'] as $answerData) {
                        if (!empty($answerData['id'])) {
                            // Mise à jour d'une réponse existante
                            $answer = Answer::find($answerData['id']);
                            $answer->text = $answerData['text'];
                            $answer->is_correct = $answerData['is_correct'];
                            $answer->save();
                        } else {
                            // Création d'une nouvelle réponse
                            Answer::create([
                                'question_id' => $question->id,
                                'text' => $answerData['text'],
                                'is_correct' => $answerData['is_correct'],
                            ]);
                        }
                    }
                } else {
                    // Création d'une nouvelle question
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'text' => $questionData['text'],
                        'type' => 'multiple_choice',
                        'order' => $index + 1,
                    ]);
                    
                    // Créer les réponses pour cette question
                    foreach ($questionData['answers'] as $answerData) {
                        Answer::create([
                            'question_id' => $question->id,
                            'text' => $answerData['text'],
                            'is_correct' => $answerData['is_correct'],
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('formateur.manage.module', ['moduleId' => $module->id])
                ->with('success', 'Le quiz a été mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur s\'est produite lors de la mise à jour du quiz. ' . $e->getMessage());
        }
    }
    
    /**
     * Supprimer un quiz et ses questions/réponses associées
     * 
     * @param  int  $quizId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyQuiz($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['questions.answers', 'module.course'])->findOrFail($quizId);
        
        // Vérifier que le quiz est bien associé à un module et que le formateur est propriétaire du cours
        if (!$quiz->module_id) {
            return redirect()->route('formateur.dashboard')->with('error', 'Ce quiz n\'est pas associé à un module');
        }
        
        $module = $quiz->module;
        
        if (!$module || ($module->course->formateur_id !== $user->id && $user->role !== 'admin')) {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à supprimer ce quiz');
        }
        
        $moduleId = $module->id;
        
        DB::beginTransaction();
        
        try {
            // Supprimer toutes les réponses aux questions
            foreach ($quiz->questions as $question) {
                $question->answers()->delete();
            }
            
            // Supprimer toutes les questions
            $quiz->questions()->delete();
            
            // Supprimer le quiz lui-même
            $quiz->delete();
            
            DB::commit();
            
            return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
                ->with('success', 'Le quiz a été supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur s\'est produite lors de la suppression du quiz. ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher le formulaire de création d'un examen final (quiz de cours)
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createFinalExam($courseId)
    {
        $user = Auth::user();
        $course = Course::with('finalQuiz')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Vérifier qu'il n'y a pas déjà un examen final
        if ($course->finalQuiz) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Ce cours a déjà un examen final.');
        }
        
        return view('formateurs.create_final_exam', compact('course'));
    }
    
    /**
     * Enregistrer un nouvel examen final (quiz de cours)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFinalExam(Request $request, $courseId)
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
        
        // Validation supplémentaire : s'assurer qu'au moins une réponse est correcte par question
        foreach ($request->questions as $index => $question) {
            $hasCorrectAnswer = collect($question['answers'])->contains('is_correct', true);
            if (!$hasCorrectAnswer) {
                return back()->withErrors([
                    "questions.{$index}" => "La question " . ($index + 1) . " doit avoir au moins une réponse correcte."
                ])->withInput();
            }
        }
        
        $user = Auth::user();
        $course = Course::with('finalQuiz')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Vérifier qu'il n'y a pas déjà un examen final
        if ($course->finalQuiz) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Ce cours a déjà un examen final.');
        }
        
        DB::beginTransaction();
        
        try {
            // Créer le quiz final
            $quiz = Quiz::create([
                'course_id' => $courseId,
                'quiz_type' => 'course_final',
                'title' => $request->title,
                'description' => $request->description,
                'passing_score' => $request->passing_score,
                'is_required' => true, // Les examens finaux sont toujours requis
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
            
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('success', 'L\'examen final a été créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur s\'est produite lors de la création de l\'examen final. ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher les détails d'un examen final pour édition
     * 
     * @param  int  $courseId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editFinalExam($courseId)
    {
        $user = Auth::user();
        $course = Course::with('finalQuiz.questions.answers')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Vérifier qu'il y a bien un examen final
        if (!$course->finalQuiz) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Ce cours n\'a pas d\'examen final.');
        }
        
        $quiz = $course->finalQuiz;
        
        return view('formateurs.edit_final_exam', compact('quiz', 'course'));
    }
    
    /**
     * Mettre à jour un examen final existant
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFinalExam(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:questions,id',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.id' => 'nullable|exists:answers,id',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required|boolean',
        ]);
        
        $user = Auth::user();
        $course = Course::with('finalQuiz.questions.answers')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Vérifier qu'il y a bien un examen final
        if (!$course->finalQuiz) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Ce cours n\'a pas d\'examen final.');
        }
        
        $quiz = $course->finalQuiz;
        
        // Mise à jour en transaction pour garantir la cohérence
        DB::beginTransaction();
        
        try {
            // Mettre à jour les informations du quiz
            $quiz->title = $request->title;
            $quiz->description = $request->description;
            $quiz->passing_score = $request->passing_score;
            $quiz->save();
            
            // Liste des IDs de questions à conserver
            $questionIds = collect($request->questions)
                ->filter(function ($q) {
                    return !empty($q['id']);
                })
                ->pluck('id')
                ->toArray();
                
            // Supprimer les questions qui ne sont plus dans la liste
            foreach ($quiz->questions as $question) {
                if (!in_array($question->id, $questionIds)) {
                    // Supprimer d'abord les réponses
                    $question->answers()->delete();
                    // Puis la question
                    $question->delete();
                }
            }
            
            // Mettre à jour ou créer les questions
            foreach ($request->questions as $index => $questionData) {
                if (!empty($questionData['id'])) {
                    // Mise à jour d'une question existante
                    $question = Question::find($questionData['id']);
                    $question->text = $questionData['text'];
                    $question->order = $index + 1;
                    $question->save();
                    
                    // Liste des IDs de réponses à conserver pour cette question
                    $answerIds = collect($questionData['answers'])
                        ->filter(function ($a) {
                            return !empty($a['id']);
                        })
                        ->pluck('id')
                        ->toArray();
                    
                    // Supprimer les réponses qui ne sont plus dans la liste
                    foreach ($question->answers as $answer) {
                        if (!in_array($answer->id, $answerIds)) {
                            $answer->delete();
                        }
                    }
                    
                    // Mettre à jour ou créer les réponses
                    foreach ($questionData['answers'] as $answerData) {
                        if (!empty($answerData['id'])) {
                            // Mise à jour d'une réponse existante
                            $answer = Answer::find($answerData['id']);
                            $answer->text = $answerData['text'];
                            $answer->is_correct = $answerData['is_correct'];
                            $answer->save();
                        } else {
                            // Création d'une nouvelle réponse
                            Answer::create([
                                'question_id' => $question->id,
                                'text' => $answerData['text'],
                                'is_correct' => $answerData['is_correct'],
                            ]);
                        }
                    }
                } else {
                    // Création d'une nouvelle question
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'text' => $questionData['text'],
                        'type' => 'multiple_choice',
                        'order' => $index + 1,
                    ]);
                    
                    // Créer les réponses pour cette question
                    foreach ($questionData['answers'] as $answerData) {
                        Answer::create([
                            'question_id' => $question->id,
                            'text' => $answerData['text'],
                            'is_correct' => $answerData['is_correct'],
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('success', 'L\'examen final a été mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur s\'est produite lors de la mise à jour de l\'examen final. ' . $e->getMessage());
        }
    }
    
    /**
     * Supprimer un examen final et ses questions/réponses associées
     * 
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFinalExam($courseId)
    {
        $user = Auth::user();
        $course = Course::with('finalQuiz.questions.answers')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Vérifier qu'il y a bien un examen final
        if (!$course->finalQuiz) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Ce cours n\'a pas d\'examen final.');
        }
        
        $quiz = $course->finalQuiz;
        
        DB::beginTransaction();
        
        try {
            // Supprimer toutes les réponses aux questions
            foreach ($quiz->questions as $question) {
                $question->answers()->delete();
            }
            
            // Supprimer toutes les questions
            $quiz->questions()->delete();
            
            // Supprimer le quiz lui-même
            $quiz->delete();
            
            DB::commit();
            
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('success', 'L\'examen final a été supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur s\'est produite lors de la suppression de l\'examen final. ' . $e->getMessage());
        }
    }

    /**
     * Afficher la liste de tous les cours du formateur avec filtres et recherche
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function coursesList(Request $request)
    {
        $user = Auth::user();
        
        $query = Course::where('formateur_id', $user->id)
            ->withCount(['students', 'modules'])
            ->with(['modules']);
        
        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filtre par niveau
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        // Tri
        $sort = $request->get('sort', 'created_desc');
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'students_desc':
                $query->orderBy('students_count', 'desc');
                break;
            default: // created_desc
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Pagination avec conservation des paramètres de recherche
        $courses = $query->paginate(9)->appends($request->query());
        
        // Ajouter les notes moyennes pour chaque cours
        $courses->getCollection()->transform(function ($course) {
            $course->average_rating = Rating::where('course_id', $course->id)->avg('rating') ?? 0;
            return $course;
        });
        
        return view('formateurs.courses.index', compact('courses'));
    }
}