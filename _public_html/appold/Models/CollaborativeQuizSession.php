<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CollaborativeQuizSession extends Model
{
    protected $fillable = [
        'quiz_id',
        'session_code',
        'started_at',
        'ended_at',
        'participants',
        'group_answers'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'participants' => 'array',
        'group_answers' => 'array'
    ];

    /**
     * Get the quiz for this session.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Create a new collaborative session.
     */
    public static function createSession(Quiz $quiz): self
    {
        return self::create([
            'quiz_id' => $quiz->id,
            'session_code' => Str::random(8),
            'started_at' => now(),
            'participants' => [],
            'group_answers' => []
        ]);
    }

    /**
     * Add a participant to the session.
     */
    public function addParticipant(User $user): void
    {
        $participants = $this->participants ?? [];
        $participants[] = [
            'user_id' => $user->id,
            'name' => $user->name,
            'joined_at' => now()->toIso8601String()
        ];
        $this->participants = $participants;
        $this->save();
    }

    /**
     * Remove a participant from the session.
     */
    public function removeParticipant(User $user): void
    {
        $participants = collect($this->participants ?? [])
            ->filter(fn($p) => $p['user_id'] !== $user->id)
            ->values()
            ->toArray();
        
        $this->participants = $participants;
        $this->save();
    }

    /**
     * Submit a group answer for a question.
     */
    public function submitGroupAnswer(int $questionId, array $answer): void
    {
        $groupAnswers = $this->group_answers ?? [];
        $groupAnswers[$questionId] = [
            'answer' => $answer,
            'submitted_at' => now()->toIso8601String(),
            'submitted_by' => auth()->id()
        ];
        $this->group_answers = $groupAnswers;
        $this->save();
    }

    /**
     * End the collaborative session.
     */
    public function endSession(): void
    {
        $this->ended_at = now();
        $this->save();
    }

    /**
     * Check if the session is active.
     */
    public function isActive(): bool
    {
        return !$this->ended_at;
    }

    /**
     * Get the current participants count.
     */
    public function getParticipantsCount(): int
    {
        return count($this->participants ?? []);
    }

    /**
     * Get the progress of the session.
     */
    public function getProgress(): array
    {
        $totalQuestions = $this->quiz->questions()->count();
        $answeredQuestions = count($this->group_answers ?? []);
        
        return [
            'total' => $totalQuestions,
            'answered' => $answeredQuestions,
            'percentage' => $totalQuestions > 0 ? ($answeredQuestions / $totalQuestions) * 100 : 0
        ];
    }

    /**
     * Get the consensus for a question.
     */
    public function getQuestionConsensus(int $questionId): ?array
    {
        $answers = $this->group_answers[$questionId] ?? null;
        if (!$answers) {
            return null;
        }

        // Logique pour déterminer le consensus basée sur le type de question
        $question = $this->quiz->questions()->find($questionId);
        if (!$question) {
            return null;
        }

        return match($question->type) {
            'multiple_choice' => $this->getMultipleChoiceConsensus($answers),
            'true_false' => $this->getTrueFalseConsensus($answers),
            'short_answer' => $this->getShortAnswerConsensus($answers),
            default => null
        };
    }

    /**
     * Get consensus for multiple choice questions.
     */
    private function getMultipleChoiceConsensus(array $answers): array
    {
        $choices = collect($answers['answer'])->countBy();
        $total = $choices->sum();
        
        return [
            'most_common' => $choices->sortDesc()->keys()->first(),
            'distribution' => $choices->map(fn($count) => ($count / $total) * 100)->toArray()
        ];
    }

    /**
     * Get consensus for true/false questions.
     */
    private function getTrueFalseConsensus(array $answers): array
    {
        $trueCount = collect($answers['answer'])->filter(fn($a) => $a === true)->count();
        $total = count($answers['answer']);
        
        return [
            'consensus' => $trueCount > ($total / 2),
            'true_percentage' => ($trueCount / $total) * 100
        ];
    }

    /**
     * Get consensus for short answer questions.
     */
    private function getShortAnswerConsensus(array $answers): array
    {
        // Pour les réponses courtes, on retourne toutes les réponses uniques
        $uniqueAnswers = collect($answers['answer'])->unique()->values();
        
        return [
            'unique_answers' => $uniqueAnswers->toArray(),
            'count' => $uniqueAnswers->count()
        ];
    }
} 