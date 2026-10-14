<?php

namespace App\Http\Controllers;

use App\Mail\CandidatureAccuseReception;
use App\Models\JobApplication;
use App\Models\JobPosition;
use App\Models\RecruitmentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RecruitmentController extends Controller
{
    public function index()
    {
        $positions = JobPosition::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (JobPosition $p) => $p->isOpen());

        return view('recruitment.index', compact('positions'));
    }

    public function form(string $slug)
    {
        $position = JobPosition::where('slug', $slug)->firstOrFail();
        if (!$position->isOpen()) {
            return redirect()->route('recruitment.index')->with('error', 'Les candidatures pour ce poste sont closes.');
        }
        return view('recruitment.form', compact('position'));
    }

    public function storeForm(Request $request, string $slug)
    {
        $position = JobPosition::where('slug', $slug)->firstOrFail();
        if (!$position->isOpen()) {
            return redirect()->route('recruitment.index')->with('error', 'Les candidatures pour ce poste sont closes.');
        }

        $rules = [
            'nom_prenoms' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'string', 'in:feminin,masculin'],
            'email' => ['required', 'email'],
            'telephone' => ['required', 'string', 'max:30'],
            'date_naissance' => ['required', 'date'],
            'adresse' => ['required', 'string', 'max:500'],
            'niveau_etudes' => ['required', 'string', 'in:CEP,BEPC,Bac,Bac+2,Bac+3,Bac+5,Autre'],
            'offer_source' => ['required', 'string', 'in:facebook,instagram,tiktok,whatsapp,site_web,recommandation,autre'],
            'offer_source_other' => ['nullable', 'required_if:offer_source,autre', 'string', 'max:100'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'lettre_motivation' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'diplome' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];

        $formDataRules = $this->getFormDataRules($slug);
        $rules = array_merge($rules, $formDataRules);

        $validated = $request->validate($rules);

        $cvPath = $request->file('cv')->store('recruitment/cv', 'public');
        $lettrePath = $request->file('lettre_motivation')->store('recruitment/lettres', 'public');

        $formData = [];
        foreach (array_keys($formDataRules) as $key) {
            if (str_starts_with($key, 'form_') && isset($validated[$key])) {
                $formData[$key] = $validated[$key];
            }
        }
        if ($request->hasFile('portfolio')) {
            $formData['portfolio_path'] = $request->file('portfolio')->store('recruitment/portfolio', 'public');
        }
        $formData['diplome_path'] = $request->file('diplome')->store('recruitment/diplomes', 'public');

        $application = JobApplication::create([
            'job_position_id' => $position->id,
            'nom_prenoms' => $validated['nom_prenoms'],
            'sexe' => $validated['sexe'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'date_naissance' => $validated['date_naissance'],
            'adresse' => $validated['adresse'],
            'niveau_etudes' => $validated['niveau_etudes'],
            'offer_source' => $validated['offer_source'],
            'offer_source_other' => $validated['offer_source_other'] ?? null,
            'form_data' => $formData,
            'cv_path' => $cvPath,
            'lettre_path' => $lettrePath,
            'status' => 'form_submitted',
        ]);

        return redirect()->route('recruitment.test', ['slug' => $slug, 'token' => $application->token])
            ->with('success', 'Votre dossier a bien été enregistré. Passez maintenant au test QCM.');
    }

    protected function getFormDataRules(string $slug): array
    {
        $rules = [];
        if ($slug === 'developpeur-fullstack') {
            $rules['form_technologie_principale'] = ['nullable', 'string', 'in:laravel,react,python,angular,wordpress'];
            $rules['form_autres_technologies'] = ['nullable', 'string', 'max:500'];
            $rules['form_lien_github'] = ['nullable', 'url', 'max:255'];
            $rules['form_lien_portfolio'] = ['nullable', 'url', 'max:255'];
            $rules['form_annees_experience'] = ['nullable', 'string', 'max:50'];
            $rules['form_projet_reel'] = ['nullable', 'in:oui,non'];
            $rules['form_description_projet'] = ['nullable', 'required_if:form_projet_reel,oui', 'string', 'max:2000'];
        }
        if ($slug === 'community-manager') {
            $rules['form_portfolio_liens'] = ['nullable', 'string', 'max:1000'];
        }
        if ($slug === 'monteur-video') {
            $rules['form_portfolio_realisations'] = ['nullable', 'string', 'max:1000'];
        }
        return $rules;
    }

    public function test(string $slug, string $token)
    {
        $position = JobPosition::where('slug', $slug)->firstOrFail();
        $application = JobApplication::where('job_position_id', $position->id)->where('token', $token)->firstOrFail();
        if ($application->status === 'test_completed') {
            return redirect()->route('recruitment.thank-you')->with('info', 'Vous avez déjà complété le test.');
        }
        $questions = $position->questions->shuffle()->values();
        if ($questions->isEmpty()) {
            return redirect()->route('recruitment.form', $slug)->with('error', 'Aucune question de test pour ce poste. Veuillez contacter l\'administration.');
        }
        foreach ($questions as $q) {
            $letters = collect(['A', 'B', 'C', 'D'])->filter(fn ($letter) => $q->getOption($letter))->shuffle()->values()->all();
            $q->setAttribute('shuffled_option_letters', $letters);
        }
        return view('recruitment.test', compact('position', 'application', 'questions'));
    }

    public function submitTest(Request $request, string $slug, string $token)
    {
        $position = JobPosition::where('slug', $slug)->firstOrFail();
        $application = JobApplication::where('job_position_id', $position->id)->where('token', $token)->firstOrFail();
        if ($application->status === 'test_completed') {
            return redirect()->route('recruitment.thank-you')
                ->with('test_score', $application->test_score)
                ->with('test_position_title', $position->title)
                ->with('test_score_level', $application->getScoreLevel());
        }

        $questions = $position->questions;
        $answers = [];
        $score = 0;
        foreach ($questions as $q) {
            $key = 'q' . $q->id;
            $answer = $request->input($key);
            if ($answer !== null) {
                $answers[$q->id] = $answer;
                if ($q->isCorrect($answer)) {
                    $score++;
                }
            }
        }
        $total = $questions->count();
        $scoreFinal = $total > 0 ? (int) round(($score / $total) * 10) : 0;
        if ($scoreFinal > 10) {
            $scoreFinal = 10;
        }

        $application->update([
            'test_answers' => $answers,
            'test_score' => $scoreFinal,
            'status' => 'test_completed',
            'submitted_at' => now(),
        ]);

        try {
            Mail::to($application->email)->send(new CandidatureAccuseReception($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('recruitment.thank-you')
            ->with('test_score', $scoreFinal)
            ->with('test_position_title', $position->title)
            ->with('test_score_level', $application->fresh()->getScoreLevel())
            ->with('success', 'Votre candidature a bien été enregistrée.');
    }

    public function results(string $slug, string $token)
    {
        $position = JobPosition::where('slug', $slug)->firstOrFail();
        $application = JobApplication::where('job_position_id', $position->id)->where('token', $token)->firstOrFail();
        if ($application->status !== 'test_completed') {
            return redirect()->route('recruitment.test', ['slug' => $slug, 'token' => $token]);
        }
        $questions = $position->questions;
        return view('recruitment.results', compact('position', 'application', 'questions'));
    }

    public function thankYou()
    {
        return view('recruitment.thank-you');
    }
}
