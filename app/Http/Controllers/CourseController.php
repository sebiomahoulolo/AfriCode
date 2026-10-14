<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    /**
     * Affiche la page d'accueil avec les cours populaires.
     */
    public function home()
    {
        // Récupérer les catégories populaires avec le nombre de cours (cache 1 heure)
        $categories = Cache::remember('popular_categories', 3600, function () {
            return Category::withCount('courses')
                ->orderBy('courses_count', 'desc')
                ->take(4)
                ->get();
        });

        // Récupérer les cours populaires (cache 30 minutes)
        $popularCourses = Cache::remember('popular_courses', 1800, function () {
            // Utiliser une sous-requête pour éviter l'erreur de GROUP BY
            $popularCoursesIds = DB::table('enrollments')
                ->select('course_id', DB::raw('COUNT(*) as count'))
                ->groupBy('course_id')
                ->orderBy('count', 'desc')
                ->limit(4)
                ->pluck('course_id');
                
            // Si aucun cours populaire n'est trouvé, récupérer les cours les plus récents
            if ($popularCoursesIds->isEmpty()) {
                return Course::where('status', 'published')
                    ->with([
                        'formateur:id,first_name,last_name', 
                        'category:id,name',
                        'ratings:id,course_id,rating',
                        'modules.lessons:id,module_id,duration_minutes' // Charger les leçons avec leurs durées
                    ])
                    ->withAvg('ratings', 'rating')
                    ->withCount(['enrollments', 'ratings'])
                    ->orderBy('published_at', 'desc')
                    ->limit(4)
                    ->get();
            } else {
                $courses = Course::whereIn('id', $popularCoursesIds)
                    ->where('status', 'published')
                    ->with([
                        'formateur:id,first_name,last_name', 
                        'category:id,name',
                        'ratings:id,course_id,rating',
                        'modules.lessons:id,module_id,duration_minutes' // Charger les leçons avec leurs durées
                    ])
                    ->withAvg('ratings', 'rating')
                    ->withCount(['enrollments', 'ratings'])
                    ->get();

                // Trier les cours dans le même ordre que les IDs récupérés
                return $courses->sortBy(function($course) use ($popularCoursesIds) {
                    return array_search($course->id, $popularCoursesIds->toArray());
                })->values();
            }
        });

        // Préparer les données des cours pour l'affichage
        $formattedCourses = $popularCourses->map(function ($course) {
            // Utiliser les vraies données de ratings au lieu de valeurs aléatoires
            $rating = round($course->ratings_avg_rating ?? 0, 1);
            $reviewsCount = $course->ratings_count ?? 0;
            
            // Définir les images statiques par défaut si aucune image n'est disponible
            $defaultImages = [
                'assets/images/th.jpeg',
                'assets/images/th (4).jpeg',
                'assets/images/télécharger.jpeg',
                'assets/images/th.jpeg',
            ];
            
            // Utiliser l'accessor pour obtenir l'URL de l'image ou une image par défaut
            if ($course->cover_image_path) {
                $imgPath = $course->cover_image_url;
            } else {
                $imgPath = asset($defaultImages[array_rand($defaultImages)]);
            }
            
            // Calculer la durée totale en additionnant toutes les durées des leçons
            $totalDurationMinutes = 0;
            foreach ($course->modules as $module) {
                foreach ($module->lessons as $lesson) {
                    $totalDurationMinutes += $lesson->duration_minutes ?? 0;
                }
            }
            
            // Convertir en heures (arrondi à l'entier supérieur)
            $durationHours = $totalDurationMinutes > 0 ? ceil($totalDurationMinutes / 60) : 0;
            
            // Si la durée est 0, essayer d'utiliser la méthode du modèle
            if ($durationHours === 0) {
                $estimatedDuration = $course->getEstimatedDuration();
                $durationHours = $estimatedDuration > 0 ? ceil($estimatedDuration / 60) : 0;
            }
            
            // Mapper les niveaux
            $levelMap = [
                'debutant' => 'DÉBUTANT',
                'intermediaire' => 'INTERMÉDIAIRE', 
                'avance' => 'AVANCÉ',
                'tous_niveaux' => 'TOUS NIVEAUX'
            ];
            
            $basePrice = (float) $course->price;
            $oldPrice = null;
            $discountPercent = 0;
            $displayPrice = $basePrice;

            if ($course->isCurrentlyOnPromotion() && $course->discount_percentage > 0) {
                $discountPercent = (int) $course->getSavingsPercentage();
                $displayPrice = (float) $course->getDiscountedPrice();
                $oldPrice = $course->original_price ? (float) $course->original_price : $basePrice;
            } elseif ($basePrice > 0) {
                $oldPrice = $basePrice * 1.5;
                $discountPercent = (int) round((1 - $basePrice / $oldPrice) * 100);
            }

            return [
                'img' => $imgPath,
                'title' => $course->title,
                'desc' => $course->short_description,
                'instructor' => $course->formateur ? ($course->formateur->first_name . ' ' . $course->formateur->last_name) : 'Formateur',
                'rating' => $rating > 0 ? $rating : 4.5,
                'reviews' => $reviewsCount,
                'price' => $displayPrice,
                'old_price' => $oldPrice,
                'discount_percentage' => $discountPercent,
                'bestseller' => $course->enrollments_count > 50,
                'new' => $course->published_at && $course->published_at->diffInDays(now()) < 30,
                'slug' => $course->slug,
                'level' => $levelMap[$course->level] ?? 'INTERMÉDIAIRE',
                'duration' => $durationHours > 0 
                    ? $durationHours . ' heure' . ($durationHours > 1 ? 's' : '') 
                    : 'Durée non spécifiée',
                'is_certifying' => $course->is_certifying,
                'category' => $course->category ? $course->category->name : 'Général'
            ];
        });

        return view('index', [
            'categories' => $categories,
            'popularCourses' => $formattedCourses
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Vérifier s'il y a un message de succès de paiement
        $paymentSuccessMessage = null;
        
        if (Auth::check()) {
            // Vérifier s'il y a un message de succès de paiement
            if (session('success') && str_contains(session('success'), 'Paiement réussi')) {
                $paymentSuccessMessage = session('success');
            }
        }
        
        return view('courses.index', compact('paymentSuccessMessage'));
    }

    /**
     * API endpoint pour les filtres Ajax
     */
    public function filter(Request $request)
    {
        $query = Course::query()
            ->where('status', 'published')
            ->with([
                'formateur:id,first_name,last_name',
                'category:id,name,slug',
            ])
            ->withAvg('ratings', 'rating')
            ->withCount([
                'enrollments as enrollments_count',
                'ratings as ratings_count'
            ]);

        // Appliquer la recherche textuelle
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('full_description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtre par catégorie (accepte ID ou slug)
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $categoryParam = $request->get('category');
            // Si c'est un nombre, c'est un ID
            if (is_numeric($categoryParam)) {
                $query->where('category_id', $categoryParam);
            } else {
                // Sinon, c'est un slug - chercher la catégorie par slug
                $category = Category::where('slug', $categoryParam)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Filtre par niveau
        if ($request->filled('level') && $request->get('level') !== 'all') {
            $query->where('level', $request->get('level'));
        }

        // Filtre par prix
        if ($request->filled('price') && $request->get('price') !== 'all') {
            $priceFilter = $request->get('price');
            if ($priceFilter === 'free') {
                $query->where('price', 0);
            } elseif ($priceFilter === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Filtre par certification
        if ($request->filled('certification') && $request->get('certification') !== 'all') {
            $certFilter = $request->get('certification');
            if ($certFilter === 'certified') {
                $query->where('is_certifying', true);
            } elseif ($certFilter === 'not_certified') {
                $query->where('is_certifying', false);
            }
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $courses = $query->paginate(12);

        // Formater les données pour l'Ajax
        $formattedCourses = $courses->map(function ($course) {
            // Calculer la durée totale en minutes
            $durationMinutes = $course->getEstimatedDuration();
            
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'short_description' => $course->short_description,
                'price' => $course->price,
                'level' => $course->level,
                'is_certifying' => $course->is_certifying,
                'cover_image_path' => $course->cover_image_path,
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
                'ratings_avg_rating' => $course->ratings_avg_rating,
                'enrollments_count' => $course->enrollments_count,
                'duration_minutes' => $durationMinutes,
                'formateur' => $course->formateur ? [
                    'id' => $course->formateur->id,
                    'first_name' => $course->formateur->first_name,
                    'last_name' => $course->formateur->last_name,
                ] : null,
                'category' => $course->category ? [
                    'id' => $course->category->id,
                    'name' => $course->category->name,
                    'slug' => $course->category->slug,
                ] : null,
            ];
        });

        // Retourner en JSON pour Ajax
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'courses' => $formattedCourses,
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'total' => $courses->total(),
                    'per_page' => $courses->perPage(),
                    'has_more_pages' => $courses->hasMorePages(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                ]
            ]);
        }

        // Fallback: rediriger vers la page principale
        return redirect()->route('courses.index');
    }

    /**
     * API endpoint pour récupérer les catégories
     */
    public function categories(Request $request)
    {
        // Cache des catégories (1 heure)
        $categories = Cache::remember('all_categories', 3600, function () {
            return Category::withCount('courses')
                ->having('courses_count', '>', 0)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'slug']);
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'categories' => $categories
            ]);
        }

        return redirect()->route('courses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        // Si l'utilisateur est connecté et formateur ou admin
        if (Auth::check() && (Auth::user()->role === 'formateur' || Auth::user()->role === 'admin')) {
            // Pour les formateurs et admins: permettre de voir tous les cours (même non publiés)
            // Les formateurs ne peuvent voir que leurs propres cours non publiés
            $course = Course::where('slug', $slug)
                ->when(Auth::user()->role === 'formateur', function ($query) {
                    return $query->where(function ($q) {
                        $q->where('status', 'published')
                        ->orWhere('formateur_id', Auth::id());
                    });
                })
                ->with(['formateur', 'modules.lessons', 'category', 'ratings', 'enrollments'])
                ->firstOrFail();
        } else {
            // Pour les utilisateurs normaux: seulement les cours publiés
            $course = Course::where('slug', $slug)
                ->where('status', 'published')
                ->with(['formateur', 'modules.lessons', 'category', 'ratings', 'enrollments'])
                ->firstOrFail();
        }

        // Vérifier les informations de paiement pour l'utilisateur connecté
        $userEnrollment = null;
        $hasValidPayment = false;
        $paymentStatus = null;
        
        if (Auth::check()) {
            $userEnrollment = $course->enrollments->where('user_id', Auth::id())->first();
            
            if ($userEnrollment) {
                // Vérifier si l'utilisateur a un paiement réussi pour ce cours
                if ($course->price > 0) {
                    // Vérifier s'il y a au moins un paiement réussi (comme dans checkCourseAccess)
                    $successfulPayment = $userEnrollment->payments()
                        ->where('status', 'succeeded')
                        ->first();
                    $hasValidPayment = $successfulPayment !== null;
                    $paymentStatus = $successfulPayment ? $successfulPayment->status : 'none';
                    
                    // Si l'utilisateur a une inscription mais pas de paiement réussi, 
                    // on laisse l'utilisateur voir la page du cours et décider de s'inscrire
                } else {
                    // Cours gratuit - accès automatique
                    $hasValidPayment = true;
                    $paymentStatus = 'free';
                }
            }
        }
            
        return view('courses.show', compact('course', 'userEnrollment', 'hasValidPayment', 'paymentStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Affiche la page de recherche avancée des formations
     * Redirige maintenant vers la page des cours avec les filtres
     */
    public function search(Request $request)
    {
        // Rediriger vers la page des cours avec les paramètres de filtrage
        $filters = $request->only(['search', 'cat', 'level', 'priceRange', 'sort']);
        
        // S'assurer qu'on a au moins un filtre actif pour déclencher l'affichage des filtres
        if (empty($filters) || (count($filters) === 1 && isset($filters['level']) && $filters['level'] === 'all')) {
            $filters['level'] = 'all'; // Force l'affichage des filtres
        }
        
        return redirect()->route('courses.index', $filters);
    }
}
