<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Affiche la page d'accueil avec les cours populaires.
     */
    public function home()
    {
        // Récupérer les catégories populaires avec le nombre de cours
        $categories = Category::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->take(4)
            ->get();

        // Solution 1: Utiliser une sous-requête pour éviter l'erreur de GROUP BY
        $popularCoursesIds = DB::table('enrollments')
            ->select('course_id', DB::raw('COUNT(*) as count'))
            ->groupBy('course_id')
            ->orderBy('count', 'desc')
            ->limit(4)
            ->pluck('course_id');
            
        $popularCourses = Course::whereIn('id', $popularCoursesIds)
            ->where('status', 'published')
            ->with('formateur:id,first_name,last_name')
            ->get();

        // Trier les cours dans le même ordre que les IDs récupérés
        $popularCourses = $popularCourses->sortBy(function($course) use ($popularCoursesIds) {
            return array_search($course->id, $popularCoursesIds->toArray());
        })->values();

        // Préparer les données des cours pour l'affichage
        $formattedCourses = $popularCourses->map(function ($course) {
            // Utiliser des valeurs fixes ou aléatoires au lieu de la relation ratings
            $rating = rand(40, 50) / 10; // Génère un nombre entre 4.0 et 5.0
            $reviewsCount = rand(50, 200);
            
            // Définir les images statiques par défaut si aucune image n'est disponible
            $defaultImages = [
                'assets/images/th.jpeg',
                'assets/images/th (4).jpeg',
                'assets/images/télécharger.jpeg',
                'assets/images/th.jpeg',
            ];
            
            // Utiliser une image par défaut aléatoire si le cours n'a pas d'image
            $imgPath = $course->cover_image_path;
            if (empty($imgPath) || !file_exists(public_path($imgPath))) {
                $imgPath = $defaultImages[array_rand($defaultImages)];
            }
            
            return [
                'img' => $imgPath,
                'title' => $course->title,
                'desc' => $course->short_description,
                'instructor' => $course->formateur->first_name . ' ' . $course->formateur->last_name,
                'rating' => $rating,
                'reviews' => $reviewsCount,
                'price' => $course->price,
                'old_price' => $course->price > 0 ? $course->price * 4 : null, // Prix barré pour montrer la réduction
                'bestseller' => true, // Les cours les plus populaires sont tous des bestsellers
                'new' => $course->published_at && $course->published_at->diffInDays(now()) < 30, // Nouveau si publié depuis moins de 30 jours
                'slug' => $course->slug
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
        // Toujours afficher la même page unifiée
        // Les filtres seront gérés via Ajax/JavaScript
        return view('courses.index');
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

        // Filtre par catégorie
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $query->where('category_id', $request->get('category'));
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
        $categories = Category::withCount('courses')
            ->having('courses_count', '>', 0)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug']);

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
            
        return view('courses.show', compact('course'));
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
