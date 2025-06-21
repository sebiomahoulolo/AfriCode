<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class QuizQuestionController extends Controller
{
    /**
     * Affiche la page de gestion des questions d'un quiz.
     */
    public function index(Quiz $quiz)
    {
        $this->authorize('create', [Question::class, $quiz]);
        
        return view('quizzes.questions', compact('quiz'));
    }

    /**
     * Stocke une nouvelle question dans le quiz.
     */
    public function store(Request $request, Quiz $quiz)
    {
        $this->authorize('create', [Question::class, $quiz]);

        $request->validate([
            'question_text' => 'required|string|max:1000',
            'type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array',
            'options.*' => 'required_if:type,multiple_choice|string|max:255',
            'correct_option' => 'required_if:type,multiple_choice,true_false',
        ]);

        try {
            DB::beginTransaction();

            $question = $quiz->questions()->create([
                'question_text' => $request->question_text,
                'type' => $request->type,
                'points' => $request->points,
                'correct_answer' => $request->type === 'true_false' ? $request->correct_option : null,
            ]);

            if ($request->type === 'multiple_choice') {
                foreach ($request->options as $index => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index == $request->correct_option,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('quizzes.questions.index', $quiz)
                ->with('success', 'Question ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout de la question.');
        }
    }

    /**
     * Met à jour une question existante.
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $this->authorize('update', $question);

        $request->validate([
            'question_text' => 'required|string|max:1000',
            'type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array',
            'options.*' => 'required_if:type,multiple_choice|string|max:255',
            'correct_option' => 'required_if:type,multiple_choice,true_false',
        ]);

        try {
            DB::beginTransaction();

            $question->update([
                'question_text' => $request->question_text,
                'type' => $request->type,
                'points' => $request->points,
                'correct_answer' => $request->type === 'true_false' ? $request->correct_option : null,
            ]);

            if ($request->type === 'multiple_choice') {
                // Supprimer les anciennes options
                $question->options()->delete();

                // Créer les nouvelles options
                foreach ($request->options as $index => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index == $request->correct_option,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('quizzes.questions.index', $quiz)
                ->with('success', 'Question mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la question.');
        }
    }

    /**
     * Supprime une question du quiz.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        $this->authorize('delete', $question);

        try {
            DB::beginTransaction();

            // Supprimer les options associées
            $question->options()->delete();
            
            // Supprimer la question
            $question->delete();

            DB::commit();

            return redirect()
                ->route('quizzes.questions.index', $quiz)
                ->with('success', 'Question supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la question.');
        }
    }

    /**
     * Réorganise l'ordre des questions.
     */
    public function reorder(Request $request, Quiz $quiz)
    {
        $this->authorize('reorder', $quiz);

        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|exists:questions,id'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->questions as $index => $questionId) {
                Question::where('id', $questionId)
                    ->where('quiz_id', $quiz->id)
                    ->update(['order' => $index + 1]);
            }

            DB::commit();

            return response()->json(['message' => 'Ordre des questions mis à jour avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Une erreur est survenue lors de la réorganisation des questions'], 500);
        }
    }
} 