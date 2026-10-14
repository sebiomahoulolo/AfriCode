<?php

namespace App\Http\Controllers;

use App\Models\Course; // Utilisation du modèle Cours

use Illuminate\Http\Request;


class CoursController extends Controller
{

    public function coursD()
    {   
        return view('pages.coursD');
    }
    public function coursT()
    {   
        return view('pages.coursT');
    }
    public function coursE()
    {   
        return view('pages.coursE');
    }
    public function compdisp()
    {  
        return view('pages.compdisp');
    }

    public function test()
    {  
        // Récupérer les cours recommandés par niveau
        $beginnerCourses = Course::where('status', 'published')
            ->where('level', 'beginner')
            ->with(['formateur', 'category', 'ratings'])
            ->take(3)
            ->get();
            
        $intermediateCourses = Course::where('status', 'published')
            ->where('level', 'intermediate')
            ->with(['formateur', 'category', 'ratings'])
            ->take(3)
            ->get();
            
        $advancedCourses = Course::where('status', 'published')
            ->where('level', 'advanced')
            ->with(['formateur', 'category', 'ratings'])
            ->take(3)
            ->get();
        
        // Si pas assez de cours par niveau, compléter avec d'autres cours
        if ($beginnerCourses->count() < 3) {
            $additionalBeginner = Course::where('status', 'published')
                ->whereNotIn('id', $beginnerCourses->pluck('id'))
                ->where('level', '!=', 'advanced')
                ->with(['formateur', 'category', 'ratings'])
                ->take(3 - $beginnerCourses->count())
                ->get();
            $beginnerCourses = $beginnerCourses->concat($additionalBeginner);
        }
        
        if ($intermediateCourses->count() < 3) {
            $additionalIntermediate = Course::where('status', 'published')
                ->whereNotIn('id', $intermediateCourses->pluck('id'))
                ->where('level', '!=', 'beginner')
                ->with(['formateur', 'category', 'ratings'])
                ->take(3 - $intermediateCourses->count())
                ->get();
            $intermediateCourses = $intermediateCourses->concat($additionalIntermediate);
        }
        
        if ($advancedCourses->count() < 3) {
            $additionalAdvanced = Course::where('status', 'published')
                ->whereNotIn('id', $advancedCourses->pluck('id'))
                ->where('level', '!=', 'beginner')
                ->with(['formateur', 'category', 'ratings'])
                ->take(3 - $advancedCourses->count())
                ->get();
            $advancedCourses = $advancedCourses->concat($additionalAdvanced);
        }
        
        // Préparer les données pour JavaScript
        $coursesData = [
            'beginner' => $beginnerCourses->map(function($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->short_description,
                    'slug' => $course->slug,
                    'price' => $course->price,
                    'instructor' => $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Équipe AfriCode',
                    'rating' => $course->ratings->avg('rating') ?? 4.5,
                    'modules_count' => $course->modules->count(),
                    'cover_image' => $course->cover_image_path ? asset($course->cover_image_path) : null,
                    'category' => $course->category ? $course->category->name : 'Développement'
                ];
            }),
            'intermediate' => $intermediateCourses->map(function($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->short_description,
                    'slug' => $course->slug,
                    'price' => $course->price,
                    'instructor' => $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Équipe AfriCode',
                    'rating' => $course->ratings->avg('rating') ?? 4.5,
                    'modules_count' => $course->modules->count(),
                    'cover_image' => $course->cover_image_path ? asset($course->cover_image_path) : null,
                    'category' => $course->category ? $course->category->name : 'Développement'
                ];
            }),
            'advanced' => $advancedCourses->map(function($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->short_description,
                    'slug' => $course->slug,
                    'price' => $course->price,
                    'instructor' => $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Équipe AfriCode',
                    'rating' => $course->ratings->avg('rating') ?? 4.5,
                    'modules_count' => $course->modules->count(),
                    'cover_image' => $course->cover_image_path ? asset($course->cover_image_path) : null,
                    'category' => $course->category ? $course->category->name : 'Développement'
                ];
            })
        ];
        
        return view('pages.test', compact('coursesData'));
    }
    public function verifier()
    {  
        return view('pages.verifier');
    }
    public function forumapp()
    {  
        return view('pages.forumapp');
    }

     public function forumexp()
    {  
        return view('pages.forumexp');
    }
    public function contact()
    {  
        return view('pages.contact');
    }

    public function apropos()
    {  
        return view('pages.apropos');
    }

    /**
     * Afficher la page détaillée d'un cours
     *
     * @param string $slug Le slug du cours
     * @return \Illuminate\View\View
     */

}
