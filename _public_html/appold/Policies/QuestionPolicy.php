<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la question.
     */
    public function view(User $user, Question $question): bool
    {
        // L'utilisateur peut voir la question s'il a accès au quiz
        return $user->can('view', $question->quiz);
    }

    /**
     * Détermine si l'utilisateur peut créer des questions.
     */
    public function create(User $user, Quiz $quiz): bool
    {
        // Seuls les instructeurs et les administrateurs peuvent créer des questions
        return $user->hasRole(['instructor', 'admin']) && $user->can('update', $quiz);
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour la question.
     */
    public function update(User $user, Question $question): bool
    {
        // Seuls les instructeurs et les administrateurs peuvent modifier les questions
        return $user->hasRole(['instructor', 'admin']) && $user->can('update', $question->quiz);
    }

    /**
     * Détermine si l'utilisateur peut supprimer la question.
     */
    public function delete(User $user, Question $question): bool
    {
        // Seuls les instructeurs et les administrateurs peuvent supprimer les questions
        return $user->hasRole(['instructor', 'admin']) && $user->can('update', $question->quiz);
    }

    /**
     * Détermine si l'utilisateur peut réorganiser les questions.
     */
    public function reorder(User $user, Quiz $quiz): bool
    {
        // Seuls les instructeurs et les administrateurs peuvent réorganiser les questions
        return $user->hasRole(['instructor', 'admin']) && $user->can('update', $quiz);
    }

    /**
     * Détermine si l'utilisateur peut répondre à la question.
     */
    public function answer(User $user, Question $question): bool
    {
        // L'utilisateur peut répondre à la question s'il a accès au quiz
        // et si le quiz n'a pas atteint sa date limite
        $quiz = $question->quiz;
        return $user->can('view', $quiz) && 
               (!$quiz->end_date || now()->lt($quiz->end_date));
    }

    /**
     * Détermine si l'utilisateur peut voir les réponses correctes.
     */
    public function viewCorrectAnswers(User $user, Question $question): bool
    {
        // L'utilisateur peut voir les réponses correctes s'il :
        // 1. Est un instructeur ou un administrateur
        // 2. A terminé le quiz et le quiz est configuré pour montrer les réponses
        $quiz = $question->quiz;
        $attempt = $quiz->attempts()->where('user_id', $user->id)->latest()->first();

        return $user->hasRole(['instructor', 'admin']) || 
               ($attempt && $attempt->is_completed && $quiz->show_correct_answers);
    }
} 