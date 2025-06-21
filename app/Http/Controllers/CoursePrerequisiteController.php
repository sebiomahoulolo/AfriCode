<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePrerequisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursePrerequisiteController extends Controller
{
    /**
     * Affiche la liste des prérequis d'un cours.
     */
    public function index(Course $course)
    {
        $this->authorize('view', $course);

        $prerequisites = $course->prerequisites()->with('prerequisiteCourse')->get();
        $availableCourses = Course::where('id', '!=', $course->id)
            ->whereNotIn('id', $prerequisites->pluck('prerequisite_course_id'))
            ->get();

        return view('courses.prerequisites.index', compact('course', 'prerequisites', 'availableCourses'));
    }

    /**
     * Affiche le formulaire de création d'un prérequis.
     */
    public function create(Course $course)
    {
        $this->authorize('update', $course);

        $availableCourses = Course::where('id', '!=', $course->id)
            ->whereNotIn('id', $course->prerequisites()->pluck('prerequisite_course_id'))
            ->get();

        return view('courses.prerequisites.create', compact('course', 'availableCourses'));
    }

    /**
     * Stocke un nouveau prérequis.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'prerequisite_course_id' => 'required|exists:courses,id',
            'type' => 'required|in:required,recommended',
            'minimum_score' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        // Vérifier que le cours prérequis n'est pas le même que le cours actuel
        if ($validated['prerequisite_course_id'] == $course->id) {
            return back()->withErrors(['prerequisite_course_id' => 'Un cours ne peut pas être son propre prérequis.']);
        }

        // Vérifier que le cours prérequis n'est pas déjà un prérequis
        if ($course->prerequisites()->where('prerequisite_course_id', $validated['prerequisite_course_id'])->exists()) {
            return back()->withErrors(['prerequisite_course_id' => 'Ce cours est déjà un prérequis.']);
        }

        $course->prerequisites()->create($validated);

        return redirect()->route('courses.prerequisites.index', $course)
            ->with('success', 'Prérequis ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un prérequis.
     */
    public function edit(Course $course, CoursePrerequisite $prerequisite)
    {
        $this->authorize('update', $course);

        return view('courses.prerequisites.edit', compact('course', 'prerequisite'));
    }

    /**
     * Met à jour un prérequis.
     */
    public function update(Request $request, Course $course, CoursePrerequisite $prerequisite)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'type' => 'required|in:required,recommended',
            'minimum_score' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $prerequisite->update($validated);

        return redirect()->route('courses.prerequisites.index', $course)
            ->with('success', 'Prérequis mis à jour avec succès.');
    }

    /**
     * Supprime un prérequis.
     */
    public function destroy(Course $course, CoursePrerequisite $prerequisite)
    {
        $this->authorize('update', $course);

        $prerequisite->delete();

        return redirect()->route('courses.prerequisites.index', $course)
            ->with('success', 'Prérequis supprimé avec succès.');
    }

    /**
     * Vérifie si l'utilisateur peut accéder au cours.
     */
    public function checkAccess(Course $course)
    {
        $user = Auth::user();
        $canAccess = $course->canBeAccessedBy($user);
        $missingPrerequisites = $course->getMissingPrerequisitesFor($user);

        return response()->json([
            'can_access' => $canAccess,
            'missing_prerequisites' => $missingPrerequisites,
        ]);
    }
} 