<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdaptiveQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'difficulty_level',
        'points',
        'conditions',
        'next_questions'
    ];

    protected $casts = [
        'conditions' => 'array',
        'next_questions' => 'array'
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Check if the question should be shown based on user performance.
     */
    public function shouldBeShown(User $user, array $previousAnswers): bool
    {
        if (empty($this->conditions)) {
            return true;
        }

        foreach ($this->conditions as $condition) {
            if (!$this->evaluateCondition($condition, $user, $previousAnswers)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a single condition.
     */
    private function evaluateCondition(array $condition, User $user, array $previousAnswers): bool
    {
        return match($condition['type']) {
            'score_threshold' => $this->evaluateScoreThreshold($condition, $previousAnswers),
            'consecutive_correct' => $this->evaluateConsecutiveCorrect($condition, $previousAnswers),
            'time_threshold' => $this->evaluateTimeThreshold($condition, $previousAnswers),
            'difficulty_progression' => $this->evaluateDifficultyProgression($condition, $previousAnswers),
            default => true
        };
    }

    /**
     * Evaluate a score threshold condition.
     */
    private function evaluateScoreThreshold(array $condition, array $previousAnswers): bool
    {
        $totalScore = collect($previousAnswers)->sum('score');
        $requiredScore = $condition['value'];
        
        return $totalScore >= $requiredScore;
    }

    /**
     * Evaluate a consecutive correct answers condition.
     */
    private function evaluateConsecutiveCorrect(array $condition, array $previousAnswers): bool
    {
        $requiredConsecutive = $condition['value'];
        $consecutiveCount = 0;
        
        foreach (array_reverse($previousAnswers) as $answer) {
            if ($answer['is_correct']) {
                $consecutiveCount++;
            } else {
                break;
            }
        }
        
        return $consecutiveCount >= $requiredConsecutive;
    }

    /**
     * Evaluate a time threshold condition.
     */
    private function evaluateTimeThreshold(array $condition, array $previousAnswers): bool
    {
        $maxTime = $condition['value'];
        $averageTime = collect($previousAnswers)->avg('time_taken');
        
        return $averageTime <= $maxTime;
    }

    /**
     * Evaluate a difficulty progression condition.
     */
    private function evaluateDifficultyProgression(array $condition, array $previousAnswers): bool
    {
        $requiredLevel = $condition['value'];
        $currentLevel = $this->getCurrentDifficultyLevel($previousAnswers);
        
        return $currentLevel >= $requiredLevel;
    }

    /**
     * Get the current difficulty level based on previous answers.
     */
    private function getCurrentDifficultyLevel(array $previousAnswers): int
    {
        $correctAnswers = collect($previousAnswers)->filter(fn($a) => $a['is_correct'])->count();
        $totalAnswers = count($previousAnswers);
        
        if ($totalAnswers === 0) {
            return 1;
        }
        
        $successRate = $correctAnswers / $totalAnswers;
        
        return match(true) {
            $successRate >= 0.8 => 3, // Difficile
            $successRate >= 0.6 => 2, // Moyen
            default => 1 // Facile
        };
    }

    /**
     * Get the next questions based on the answer.
     */
    public function getNextQuestions(bool $isCorrect): array
    {
        $nextQuestions = $this->next_questions ?? [];
        
        return match($isCorrect) {
            true => $nextQuestions['correct'] ?? [],
            false => $nextQuestions['incorrect'] ?? []
        };
    }

    /**
     * Get the points for this question.
     */
    public function getPoints(): int
    {
        return match($this->difficulty_level) {
            'easy' => $this->points,
            'medium' => $this->points * 1.5,
            'hard' => $this->points * 2,
            default => $this->points
        };
    }

    /**
     * Get a human-readable description of the conditions.
     */
    public function getConditionsDescription(): string
    {
        if (empty($this->conditions)) {
            return 'Aucune condition requise';
        }

        $descriptions = [];
        foreach ($this->conditions as $condition) {
            $descriptions[] = match($condition['type']) {
                'score_threshold' => sprintf('Score minimum de %d points', $condition['value']),
                'consecutive_correct' => sprintf('%d réponses correctes consécutives', $condition['value']),
                'time_threshold' => sprintf('Temps moyen de réponse inférieur à %d secondes', $condition['value']),
                'difficulty_progression' => sprintf('Niveau de difficulté %d atteint', $condition['value']),
                default => 'Condition non définie'
            };
        }

        return implode(', ', $descriptions);
    }
} 