<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Competition;
use Illuminate\Http\Request;

class AdminChallengeController extends Controller
{
    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        if ($type === 'challenge') {
            $challenge = new Challenge();
            $challenge->name = $request->input('name');
            $challenge->description = $request->input('description');
            $challenge->type = $request->input('type_challenge', 'special');
            $challenge->difficulty = $request->input('difficulty');
            $challenge->time_limit_minutes = $request->input('time_limit_minutes');
            $challenge->is_active = true;
            // Gestion requirements
            $requirementsInput = $request->input('requirements');
            if ($requirementsInput) {
                $decoded = json_decode($requirementsInput, true);
                $challenge->requirements = $decoded !== null ? $decoded : $requirementsInput;
            } else {
                $challenge->requirements = [];
            }
            // Gestion rewards
            $rewardsInput = $request->input('rewards');
            if ($rewardsInput) {
                $decoded = json_decode($rewardsInput, true);
                $challenge->rewards = $decoded !== null ? $decoded : $rewardsInput;
            } else {
                $challenge->rewards = [];
            }
            $challenge->save();
            return redirect()->route('admin.challenges.create')->with(['success' => 'Défi créé avec succès !', 'challenge_id' => $challenge->id]);
        } elseif ($type === 'competition') {
            $competition = new Competition();
            $competition->title = $request->input('name');
            $competition->description = $request->input('description');
            $competition->start_datetime = $request->input('start_datetime');
            $competition->end_datetime = $request->input('end_datetime');
            $competition->max_participants = $request->input('max_participants');
            $competition->status = 'upcoming';
            $competition->organizer_id = auth()->id();
            // Générer un slug unique
            $competition->slug = \Str::slug($competition->title);
            $competition->save();
            return redirect()->route('admin.challenges.create')->with('success', 'Compétition créée avec succès !');
        }
        return back()->with('error', 'Type non reconnu.');
    }

    public function index()
    {
        $challenges = \App\Models\Challenge::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.challenges.index', compact('challenges'));
    }

    public function questions($challengeId)
    {
        $challenge = \App\Models\Challenge::findOrFail($challengeId);
        $questions = $challenge->questions()->with('options')->get();
        return view('admin.challenges.questions', compact('challenge', 'questions'));
    }

    public function storeQuestion(Request $request, $challengeId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'nullable|boolean',
        ]);
        $question = \App\Models\ChallengeQuestion::create([
            'challenge_id' => $challengeId,
            'question_text' => $request->question_text,
        ]);
        foreach ($request->options as $opt) {
            $question->options()->create([
                'option_text' => $opt['option_text'],
                'is_correct' => !empty($opt['is_correct']),
            ]);
        }
        return redirect()->route('admin.challenges.questions', $challengeId)->with('success', 'Question ajoutée !');
    }

    public function destroyQuestion($challengeId, $questionId)
    {
        $question = \App\Models\ChallengeQuestion::where('challenge_id', $challengeId)->findOrFail($questionId);
        $question->options()->delete();
        $question->delete();
        return redirect()->route('admin.challenges.questions', $challengeId)->with('success', 'Question supprimée.');
    }
} 