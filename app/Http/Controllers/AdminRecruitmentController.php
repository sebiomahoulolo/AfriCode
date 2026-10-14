<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminRecruitmentController extends Controller
{
    /**
     * Vue d'accueil recrutement : postes et statistiques.
     */
    public function index(): View
    {
        $positions = JobPosition::orderBy('sort_order')->withCount('applications')->get();
        $totalApplications = JobApplication::count();
        $completedCount = JobApplication::where('status', 'test_completed')->count();
        $recentApplications = JobApplication::with('jobPosition')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.recruitment.index', compact(
            'positions',
            'totalApplications',
            'completedCount',
            'recentApplications'
        ));
    }

    /**
     * Formulaire d'édition d'un poste (dates limites, places, statut, contenu).
     */
    public function positionEdit(JobPosition $position): View
    {
        return view('admin.recruitment.positions.edit', compact('position'));
    }

    /**
     * Mise à jour d'un poste, notamment sa date limite de candidature.
     */
    public function positionUpdate(Request $request, JobPosition $position): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'places_count' => ['required', 'integer', 'min:1'],
            'deadline' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'missions' => ['nullable', 'string'],
            'profil' => ['nullable', 'string'],
            'dossier_fournir' => ['nullable', 'string'],
        ]);

        $position->update([
            'title' => $validated['title'],
            'places_count' => $validated['places_count'],
            'deadline' => $validated['deadline'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'missions' => $validated['missions'] ?? null,
            'profil' => $validated['profil'] ?? null,
            'dossier_fournir' => $validated['dossier_fournir'] ?? null,
        ]);

        return redirect()->route('admin.recruitment.index')->with('success', 'Poste mis à jour avec succès.');
    }

    /**
     * Liste des candidatures (toutes ou par poste).
     */
    public function applications(Request $request): View
    {
        $query = JobApplication::with('jobPosition')->orderByDesc('submitted_at')->orderByDesc('created_at');

        if ($request->filled('position')) {
            $query->where('job_position_id', $request->position);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qry) use ($q) {
                $qry->where('nom_prenoms', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telephone', 'like', "%{$q}%");
            });
        }

        $applications = $query->paginate(20)->withQueryString();
        $positions = JobPosition::orderBy('sort_order')->get();

        return view('admin.recruitment.applications.index', compact('applications', 'positions'));
    }

    /**
     * Détail d'une candidature.
     */
    public function applicationShow(JobApplication $application): View
    {
        $application->load('jobPosition');
        return view('admin.recruitment.applications.show', compact('application'));
    }

    /**
     * Téléchargement sécurisé d'un document (CV, lettre, diplôme).
     */
    public function document(JobApplication $application, string $type): Response
    {
        $path = match ($type) {
            'cv' => $application->cv_path,
            'lettre' => $application->lettre_path,
            'diplome' => $application->form_data['diplome_path'] ?? null,
            default => null,
        };

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'Document introuvable.');
        }

        $fullPath = Storage::disk('public')->path($path);
        $filename = basename($path);

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
