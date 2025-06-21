<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::with(['course', 'questions'])
            ->whereHas('course', function ($query) {
                $query->where('is_published', true);
            })
            ->paginate(10);

        return view('quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Quiz::class);
        return view('quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Quiz::class);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'allow_retake' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.required' => 'boolean',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice,true_false|array',
            'questions.*.options.*.option' => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean'
        ]);

        DB::transaction(function () use ($validated) {
            $quiz = Quiz::create([
                'course_id' => $validated['course_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'time_limit' => $validated['time_limit'],
                'passing_score' => $validated['passing_score'],
                'randomize_questions' => $validated['randomize_questions'],
                'show_correct_answers' => $validated['show_correct_answers'],
                'allow_retake' => $validated['allow_retake'],
                'max_attempts' => $validated['max_attempts']
            ]);

            foreach ($validated['questions'] as $questionData) {
                $question = $quiz->questions()->create([
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
                    'required' => $questionData['required']
                ]);

                if (isset($questionData['options'])) {
                    foreach ($questionData['options'] as $optionData) {
                        $question->options()->create([
                            'option' => $optionData['option'],
                            'is_correct' => $optionData['is_correct']
                        ]);
                    }
                }
            }
        });

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        $quiz->load(['questions.options', 'course']);
        $userAttempts = $quiz->attempts()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('quizzes.show', compact('quiz', 'userAttempts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $quiz->load(['questions.options']);
        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'allow_retake' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1'
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz supprimé avec succès !');
    }

    public function start(Quiz $quiz)
    {
        $this->authorize('attempt', $quiz);

        if (!$quiz->canBeAttemptedBy(Auth::user())) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'Vous ne pouvez plus tenter ce quiz.');
        }

        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'started_at' => now()
        ]);

        return redirect()->route('quizzes.attempt', $attempt);
    }

    public function attempt(QuizAttempt $attempt)
    {
        $this->authorize('view', $attempt);

        if ($attempt->completed_at) {
            return redirect()->route('quizzes.results', $attempt);
        }

        if ($attempt->isTimeUp()) {
            $attempt->complete();
            return redirect()->route('quizzes.results', $attempt)
                ->with('warning', 'Le temps est écoulé !');
        }

        $questions = $attempt->quiz->getQuestionsForAttempt();
        $timeRemaining = $attempt->getTimeRemaining();

        return view('quizzes.attempt', compact('attempt', 'questions', 'timeRemaining'));
    }

    public function submit(Request $request, QuizAttempt $attempt)
    {
        $this->authorize('update', $attempt);

        if ($attempt->completed_at) {
            return redirect()->route('quizzes.results', $attempt);
        }

        $answers = $request->input('answers', []);
        $questions = $attempt->quiz->questions;

        DB::transaction(function () use ($attempt, $answers, $questions) {
            foreach ($questions as $question) {
                $answer = $answers[$question->id] ?? null;
                
                if ($answer === null && $question->required) {
                    throw new \Exception("La question {$question->id} est requise.");
                }

                $isCorrect = $question->validateAnswer($answer);
                $pointsEarned = $isCorrect ? $question->points : 0;

                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'answer' => $answer,
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned
                ]);
            }

            $attempt->complete();
        });

        return redirect()->route('quizzes.results', $attempt)
            ->with('success', 'Quiz terminé avec succès !');
    }

    public function results(QuizAttempt $attempt)
    {
        $this->authorize('view', $attempt);

        $attempt->load(['quiz.questions.options', 'answers']);
        $score = $attempt->score;
        $totalPoints = $attempt->quiz->total_points;
        $percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;

        return view('quizzes.results', compact('attempt', 'score', 'totalPoints', 'percentage'));
    }
}
