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
use App\Models\FormateurEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;

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
        
        // Initialiser les variables avec des valeurs par défaut
        $courses = collect();
        $totalRevenue = 0;
        $totalEnrollments = 0;
        $averageRating = 0;
        $recentActivities = collect();
        
        try {
            // Vérifier que l'utilisateur est bien un formateur
            if (!$user || $user->role !== 'formateur') {
                \Log::warning('Tentative d\'accès au dashboard formateur par un utilisateur non-formateur: ' . ($user ? $user->id : 'null'));
                return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
            }
            
            // Vérifier si le profil est complet
            $isProfileComplete = $this->checkProfileCompletion($user);
            $missingFields = $this->getMissingProfileFields($user);
            
            // Récupérer les cours créés par le formateur
            $courses = Course::where('formateur_id', $user->id)
                ->withCount('students')
                ->with(['modules', 'category'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Récupérer les cours avec des messages de validation (corrections, rejets, ou en attente)
            // Exclure les cours déjà validés et publiés
            $coursesWithMessages = $courses->filter(function($course) {
                // Exclure les cours déjà validés et publiés
                if ($course->validation_status === 'approved' && $course->status === 'published') {
                    return false;
                }
                
                // Inclure seulement les cours qui nécessitent une action
                return ($course->validation_status === 'needs_revision' && $course->revision_notes) ||
                       ($course->validation_status === 'rejected') ||
                       ($course->validation_status === 'pending_validation');
            });
            
            // Calculer les revenus totaux (si applicable)
            if ($courses->isNotEmpty()) {
                $courseIds = $courses->pluck('id');
                
                // Méthode 1 : Depuis les paiements directs (77% du montant total)
                $earningsFromPayments = Payment::where('payable_type', \App\Models\Course::class)
                    ->whereIn('payable_id', $courseIds)
                    ->where('status', 'succeeded')
                    ->sum('amount');
                // Calculer 77% pour le formateur
                $formateurShareFromPayments = $earningsFromPayments * 0.77;
                
                // Méthode 2 : Depuis formateur_earnings (si disponible)
                $earningsFromFormateurEarnings = FormateurEarning::where('formateur_id', $user->id)
                    ->whereIn('course_id', $courseIds)
                    ->where('payment_status', 'paid')
                    ->sum('total_earnings');
                
                // Utiliser la méthode qui donne le meilleur résultat
                $totalRevenue = max($formateurShareFromPayments, $earningsFromFormateurEarnings);
                
                // Si aucune méthode ne fonctionne, utiliser la valeur stockée dans le profil
                if ($totalRevenue == 0) {
                    $totalRevenue = $user->total_earnings ?? 0;
                }
                    
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
            }
            
            return view('formateurs.dashboard', compact(
                'courses', 
                'totalRevenue', 
                'totalEnrollments', 
                'averageRating', 
                'recentActivities',
                'coursesWithMessages',
                'isProfileComplete',
                'missingFields'
            ));
            
        } catch (\Exception $e) {
            // Log l'erreur avec plus de détails
            \Log::error('Erreur dans FormateurController::index: ' . $e->getMessage(), [
                'user_id' => $user ? $user->id : 'null',
                'user_role' => $user ? $user->role : 'null',
                'trace' => $e->getTraceAsString()
            ]);
            
            // Retourner la vue avec des données par défaut et un message d'erreur
            $coursesWithMessages = collect();
            $isProfileComplete = false;
            $missingFields = [];
            return view('formateurs.dashboard', compact(
                'courses', 
                'totalRevenue', 
                'totalEnrollments',
                'coursesWithMessages', 
                'averageRating', 
                'recentActivities',
                'isProfileComplete',
                'missingFields'
            ))->with('error', 'Erreur lors du chargement des données: ' . $e->getMessage());
        }
    }
    
    /**
     * Vérifier si le profil du formateur est complet
     */
    private function checkProfileCompletion($user)
    {
        $requiredFields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'bio',
            'city',
            'country',
            'payment_method',
            'payment_account'
        ];

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * Obtenir la liste des champs manquants dans le profil
     */
    private function getMissingProfileFields($user)
    {
        $requiredFields = [
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'bio' => 'Biographie',
            'city' => 'Ville',
            'country' => 'Pays',
            'payment_method' => 'Méthode de paiement',
            'payment_account' => 'Compte de paiement'
        ];

        $missing = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($user->$field)) {
                $missing[] = $label;
            }
        }

        return $missing;
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
        $user = Auth::user();
        
        // Vérifier si le profil est complet
        $isProfileComplete = $this->checkProfileCompletion($user);
        
        if (!$isProfileComplete) {
            $missingFields = $this->getMissingProfileFields($user);
            return redirect()->route('formateur.dashboard')
                ->with('error', 'Veuillez compléter votre profil avant de créer un cours. Informations manquantes: ' . implode(', ', $missingFields));
        }
        
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
        $user = Auth::user();
        
        // Vérifier si le profil est complet
        $isProfileComplete = $this->checkProfileCompletion($user);
        
        if (!$isProfileComplete) {
            $missingFields = $this->getMissingProfileFields($user);
            return redirect()->route('formateur.dashboard')
                ->with('error', 'Veuillez compléter votre profil avant de créer un cours. Informations manquantes: ' . implode(', ', $missingFields));
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'level' => 'required|string|in:debutant,intermediaire,avance,tous_niveaux',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
            ],
            'currency' => 'required|string|size:3',
            'is_certifying' => 'nullable|boolean',
        ]);
        
        // Validation supplémentaire du type MIME
        if ($request->hasFile('cover_image')) {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $mimeType = $request->file('cover_image')->getMimeType();
            if (!in_array($mimeType, $allowedMimes)) {
                return back()->withErrors(['cover_image' => 'Type de fichier non autorisé. Formats acceptés: JPEG, PNG, GIF, WebP.'])->withInput();
            }
        }
        
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
            try {
                $file = $request->file('cover_image');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                $coverImagePath = $file->storeAs('courses', $filename, 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage()])->withInput();
            }
        }
        
        // Créer le cours
        try {
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
            
            // Déclencher l'événement CourseCreated
            event(new \App\Events\CourseCreated($course));
            
            return redirect()->route('formateur.modules.create', ['courseId' => $course->id])
                ->with('success', 'Le cours a été créé avec succès. Vous pouvez maintenant ajouter des modules.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création du cours: ' . $e->getMessage()])->withInput();
        }
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
            'level' => 'required|string|in:debutant,intermediaire,avance,tous_niveaux',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=400,min_height=300,max_width=2000,max_height=1500'
            ],
            'is_certifying' => 'nullable|boolean',
        ]);
        
        // Validation supplémentaire du type MIME
        if ($request->hasFile('cover_image')) {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $mimeType = $request->file('cover_image')->getMimeType();
            if (!in_array($mimeType, $allowedMimes)) {
                return back()->withErrors(['cover_image' => 'Type de fichier non autorisé. Formats acceptés: JPEG, PNG, GIF, WebP.'])->withInput();
            }
        }
        
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
            if ($course->cover_image_path) {
                $oldPath = public_path($course->cover_image_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            
            // Sauvegarder la nouvelle image
            $file = $request->file('cover_image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Créer le dossier s'il n'existe pas
            $destinationPath = public_path('storage/courses');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            // Déplacer le fichier vers public/storage/courses
            $file->move($destinationPath, $filename);
            $course->cover_image_path = 'storage/courses/' . $filename;
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
     * Soumettre un cours pour validation
     * 
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForValidation($courseId)
    {
        $user = Auth::user();
        $course = Course::with('modules.lessons')->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce cours');
        }
        
        // Vérifier que le cours a au moins un module avec des leçons
        if ($course->modules->isEmpty() || $course->modules->every(function($module) {
            return $module->lessons->isEmpty();
        })) {
            return back()->with('error', 'Le cours doit contenir au moins un module avec des leçons avant d\'être soumis pour validation.');
        }
        
        // Sauvegarder l'ancien statut pour le message
        $previousStatus = $course->validation_status;
        
        // Vérifier l'onboarding seulement pour la première soumission (pas pour les resoumissions)
        // Si le cours a déjà été soumis une fois (rejected, needs_revision, ou même draft avec submitted_for_validation_at),
        // on considère que l'onboarding a été complété
        $isResubmission = in_array($previousStatus, ['rejected', 'needs_revision']) || 
                         ($course->submitted_for_validation_at !== null);
        
        if (!$isResubmission && !$user->onboarding_completed) {
            return back()->with('error', 'Vous devez compléter l\'onboarding avant de soumettre un cours.');
        }
        
        // Si le cours est déjà publié et validé, ne pas permettre la resoumission
        if ($course->status === 'published' && $course->validation_status === 'approved') {
            return back()->with('error', 'Ce cours est déjà publié et validé.');
        }
        
        // Si le cours est déjà en attente de validation, ne pas permettre une nouvelle soumission
        if ($course->validation_status === 'pending_validation') {
            return back()->with('error', 'Ce cours est déjà en attente de validation.');
        }
        
        // Utiliser DB::table() pour mettre à jour directement et contourner le problème de fillable
        $updated = DB::table('courses')
            ->where('id', $courseId)
            ->update([
                'validation_status' => 'pending_validation',
                'submitted_for_validation_at' => now(),
                'status' => 'draft', // S'assurer que le statut reste en brouillon jusqu'à validation
                'updated_at' => now()
            ]);
        
        if ($updated === false) {
            return back()->with('error', 'Erreur lors de la soumission du cours. Veuillez réessayer.');
        }

        // Recharger le cours pour avoir submitted_for_validation_at
        $course->refresh();

        // Notifier les administrateurs par email
        try {
            app(\App\Services\CourseMailService::class)->sendCourseSubmittedToAdmins($course);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notification admin (cours soumis) non envoyée: ' . $e->getMessage());
        }
        
        // Message selon le contexte
        $message = 'Cours soumis pour validation. Vous recevrez une réponse sous 24h.';
        if ($previousStatus === 'rejected' || $previousStatus === 'needs_revision') {
            $message = 'Cours resoumis pour validation. Il sera examiné à nouveau.';
        }
        
        return back()->with('success', $message);
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
     * Afficher le formulaire de modification d'un module
     * 
     * @param  int  $moduleId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editModule($moduleId)
    {
        $user = Auth::user();
        $module = Module::with(['course', 'lessons', 'quiz'])->findOrFail($moduleId);
        $course = $module->course;
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce module');
        }
        
        return view('formateurs.edit_module', compact('module', 'course'));
    }
    
    /**
     * Mettre à jour un module existant
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateModule(Request $request, $moduleId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
        ]);
        
        $user = Auth::user();
        $module = Module::findOrFail($moduleId);
        $course = $module->course;
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
        
        // Mettre à jour le module
        $module->update([
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? $module->order,
        ]);
        
        return redirect()->route('formateur.manage.module', ['moduleId' => $moduleId])
            ->with('success', 'Le module a été modifié avec succès.');
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
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($module->course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à gérer ce module');
        }
          // Récupérer le cours lié au module
    $course = $module->course;
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
        
        // Récupérer les images temporaires de la session
        $images = session('temp_lesson_images', []);
        
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
            'images' => $images,
        ]);
        
        // Nettoyer la session des images temporaires
        session()->forget('temp_lesson_images');
        
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
        $course = $module->course; 
        
        return view('formateurs.create_quiz', compact('module', 'course'));
    }
    
    /**
     * Enregistrer un nouveau quiz
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeQuiz(StoreQuizRequest $request, $moduleId)
    {
        $validated = $request->validated();
        
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
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'passing_score' => $validated['passing_score'],
                'is_required' => true, // Les quiz de module sont obligatoires par défaut
            ]);
            
            // Créer les questions et les réponses
            foreach ($validated['questions'] as $questionData) {
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
        
        // Utiliser la méthode submitForValidation pour la cohérence
        return $this->submitForValidation($courseId);
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
     * Retirer un étudiant d'un cours (supprimer l'inscription)
     */
    public function deleteEnrollment(int $enrollmentId)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);
        $course = $enrollment->course;

        // Sécurité : seul le formateur propriétaire peut retirer un étudiant
        if ($course->formateur_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $courseId = $course->id;
        $enrollment->delete();

        return redirect()->route('formateur.courses.students', $courseId)
            ->with('success', 'L\'étudiant a été retiré du cours avec succès.');
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
        
        // Calculer la note moyenne
        $averageRating = Rating::where('course_id', $courseId)->avg('rating') ?? 0;
        
        return view('formateurs.course_ratings', compact('course', 'ratings', 'averageRating'));
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
        
        $totalRevenue = Payment::where('payable_type', \App\Models\Course::class)
            ->where('payable_id', $courseId)
            ->where('status', 'succeeded')
            ->sum('amount');
        
        // Regrouper les paiements par mois pour le graphique
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
            if ($course->cover_image_path) {
                $imagePath = public_path($course->cover_image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
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
        
        // Récupérer les images temporaires de la session
        $images = session('temp_lesson_images', []);
        
        // Fusionner avec les images existantes si elles existent
        $existingImages = $lesson->images ?? [];
        $allImages = array_merge($existingImages, $images);
        
        $lesson->content_type = $request->content_type;
        $lesson->duration_minutes = $request->duration_minutes;
        $lesson->is_previewable = $request->has('is_previewable');
        $lesson->images = $allImages;
        $lesson->save();
        
        // Nettoyer la session des images temporaires
        session()->forget('temp_lesson_images');
        
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
    public function updateQuiz(UpdateQuizRequest $request, $quizId)
    {
        $validated = $request->validated();
        
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
    public function storeFinalExam(StoreQuizRequest $request, $courseId)
    {
        $validated = $request->validated();
        
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
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'passing_score' => $validated['passing_score'],
                'is_required' => true, // Les examens finaux sont toujours requis
            ]);
            
            // Créer les questions et les réponses
            foreach ($validated['questions'] as $questionData) {
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
    public function updateFinalExam(UpdateQuizRequest $request, $courseId)
    {
        $validated = $request->validated();
        
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
            $quiz->title = $validated['title'];
            $quiz->description = $validated['description'] ?? null;
            $quiz->passing_score = $validated['passing_score'];
            $quiz->save();
            
            // Liste des IDs de questions à conserver
            $questionIds = collect($validated['questions'])
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
            foreach ($validated['questions'] as $index => $questionData) {
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
        
        // Filtre par niveau (mapper les valeurs de la vue vers la BDD)
        if ($request->filled('level')) {
            $levelMap = [
                'beginner' => 'débutant',
                'intermediate' => 'intermédiaire',
                'advanced' => 'avancé',
                'expert' => 'expert'
            ];
            $level = $levelMap[$request->level] ?? $request->level;
            $query->where('level', $level);
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
        $perPage = $request->get('per_page', 9);
        $courses = $query->paginate($perPage);
        
        // Optimiser le calcul des notes moyennes (éviter N+1)
        if ($courses->count() > 0) {
            $courseIds = $courses->pluck('id');
            $ratings = Rating::whereIn('course_id', $courseIds)
                ->selectRaw('course_id, AVG(rating) as avg_rating')
                ->groupBy('course_id')
                ->pluck('avg_rating', 'course_id');
            
            // Ajouter les notes moyennes pour chaque cours
            $courses->getCollection()->transform(function ($course) use ($ratings) {
                $course->average_rating = round($ratings[$course->id] ?? 0, 1);
                return $course;
            });
        }
        
        return view('formateurs.courses.index', compact('courses'));
    }
    
    /**
     * Afficher la page du guide et du contrat
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showGuide()
    {
        return view('formateurs.guide');
    }
    
    /**
     * Voir une leçon comme un étudiant (aperçu formateur)
     * 
     * @param int $courseId
     * @param int $lessonId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewLessonAsStudent($courseId, $lessonId)
    {
        $user = Auth::user();
        $course = Course::with(['modules.lessons', 'modules.quiz', 'finalQuiz'])->findOrFail($courseId);
        
        // Vérifier que le formateur est bien le propriétaire du cours
        if ($course->formateur_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('formateur.dashboard')->with('error', 'Vous n\'êtes pas autorisé à voir ce cours');
        }
        
        $lesson = Lesson::with('module')->findOrFail($lessonId);
        
        // Vérifier que la leçon appartient bien au cours
        if ($lesson->module->course_id !== $course->id) {
            return redirect()->route('formateur.manage.course', ['courseId' => $courseId])
                ->with('error', 'Cette leçon n\'appartient pas à ce cours');
        }
        
        // Construire la structure du cours (comme pour l'apprenant mais sans les verrous)
        $courseStructure = [
            'modules' => [],
            'final_quiz' => null,
        ];
        
        foreach ($course->modules->sortBy('order') as $module) {
            $moduleData = [
                'module' => $module,
                'lessons' => [],
                'quiz' => null,
            ];
            
            foreach ($module->lessons->sortBy('order') as $l) {
                $moduleData['lessons'][] = [
                    'item' => $l,
                    'status' => 'available',
                    'is_completed' => false,
                ];
            }
            
            if ($module->quiz) {
                $moduleData['quiz'] = [
                    'item' => $module->quiz,
                    'status' => 'available',
                    'is_completed' => false,
                ];
            }
            
            $courseStructure['modules'][] = $moduleData;
        }
        
        if ($course->finalQuiz) {
            $courseStructure['final_quiz'] = [
                'item' => $course->finalQuiz,
                'status' => 'available',
                'is_completed' => false,
            ];
        }
        
        // Déterminer la leçon précédente et suivante
        $allLessons = $course->modules->sortBy('order')->flatMap(function ($module) {
            return $module->lessons->sortBy('order');
        });
        
        $lessonIds = $allLessons->pluck('id')->toArray();
        $currentIndex = array_search($lesson->id, $lessonIds);
        
        $previousLesson = $currentIndex > 0 ? $allLessons->firstWhere('id', $lessonIds[$currentIndex - 1]) : null;
        $nextLesson = $currentIndex < count($lessonIds) - 1 ? $allLessons->firstWhere('id', $lessonIds[$currentIndex + 1]) : null;
        
        // Déterminer si c'est la dernière leçon du module
        $moduleLessons = $lesson->module->lessons->sortBy('order');
        $isLastLessonOfModule = $moduleLessons->last()->id === $lesson->id;
        $moduleQuiz = $lesson->module->quiz;
        
        // Première leçon du module suivant
        $firstLessonNextModule = null;
        $currentModuleOrder = $lesson->module->order;
        $nextModule = $course->modules->where('order', '>', $currentModuleOrder)->sortBy('order')->first();
        if ($nextModule && $nextModule->lessons->count() > 0) {
            $firstLessonNextModule = $nextModule->lessons->sortBy('order')->first();
        }
        
        return view('formateurs.view_lesson_as_student', compact(
            'course',
            'lesson',
            'courseStructure',
            'previousLesson',
            'nextLesson',
            'isLastLessonOfModule',
            'moduleQuiz',
            'firstLessonNextModule'
        ));
    }
}