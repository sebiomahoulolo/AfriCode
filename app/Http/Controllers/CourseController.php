<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index()
    {
        $courses = Course::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('courses.index', compact('courses'));
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
        $course = Course::where('slug', $slug)
            ->where('status', 'published')
            ->with(['formateur', 'modules.lessons', 'category', 'ratings', 'enrollments'])
            ->firstOrFail();
            
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
     */
    public function search()
    {
        // Cette méthode affiche simplement la vue qui contient le composant Livewire
        // Le composant Livewire CoursesFilterSearch gère la recherche et le filtrage
        return view('courses.search');
    }
}
