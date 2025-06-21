<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RealtimeQuizSession extends Model
{
    protected $fillable = [
        'quiz_id',
        'session_code',
        'scheduled_start',
        'actual_start',
        'ended_at',
        'participants',
        'leaderboard'
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'actual_start' => 'datetime',
        'ended_at' => 'datetime',
        'participants' => 'array',
        'leaderboard' => 'array'
    ];

    /**
     * Get the quiz for this session.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Create a new realtime session.
     */
    public static function createSession(Quiz $quiz, \DateTime $scheduledStart): self
    {
        return self::create([
            'quiz_id' => $quiz->id,
            'session_code' => Str::random(8),
            'scheduled_start' => $scheduledStart,
            'participants' => [],
            'leaderboard' => []
        ]);
    }

    /**
     * Start the session.
     */
    public function start(): void
    {
        $this->actual_start = now();
        $this->save();
    }

    /**
     * End the session.
     */
    public function end(): void
    {
        $this->ended_at = now();
        $this->save();
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
            'joined_at' => now()->toIso8601String(),
            'score' => 0,
            'answers' => []
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
     * Submit an answer for a participant.
     */
    public function submitAnswer(User $user, int $questionId, $answer): void
    {
        $participants = $this->participants ?? [];
        $participantIndex = collect($participants)->search(fn($p) => $p['user_id'] === $user->id);
        
        if ($participantIndex !== false) {
            $participants[$participantIndex]['answers'][$questionId] = [
                'answer' => $answer,
                'submitted_at' => now()->toIso8601String()
            ];
            
            // Mettre à jour le score
            $question = $this->quiz->questions()->find($questionId);
            if ($question && $question->isCorrect($answer)) {
                $participants[$participantIndex]['score'] += $question->points;
            }
            
            $this->participants = $participants;
            $this->updateLeaderboard();
            $this->save();
        }
    }

    /**
     * Update the leaderboard.
     */
    private function updateLeaderboard(): void
    {
        $leaderboard = collect($this->participants ?? [])
            ->sortByDesc('score')
            ->take(10)
            ->map(fn($p) => [
                'user_id' => $p['user_id'],
                'name' => $p['name'],
                'score' => $p['score']
            ])
            ->values()
            ->toArray();
        
        $this->leaderboard = $leaderboard;
    }

    /**
     * Check if the session is active.
     */
    public function isActive(): bool
    {
        return $this->actual_start && !$this->ended_at;
    }

    /**
     * Check if the session is scheduled.
     */
    public function isScheduled(): bool
    {
        return !$this->actual_start && !$this->ended_at;
    }

    /**
     * Get the time until start.
     */
    public function getTimeUntilStart(): ?int
    {
        if (!$this->isScheduled()) {
            return null;
        }

        return max(0, now()->diffInSeconds($this->scheduled_start));
    }

    /**
     * Get the current question.
     */
    public function getCurrentQuestion(): ?Question
    {
        if (!$this->isActive()) {
            return null;
        }

        $elapsedTime = now()->diffInSeconds($this->actual_start);
        $questionIndex = floor($elapsedTime / 30); // 30 secondes par question
        
        return $this->quiz->questions()->skip($questionIndex)->first();
    }

    /**
     * Get the time remaining for the current question.
     */
    public function getTimeRemainingForCurrentQuestion(): ?int
    {
        if (!$this->isActive()) {
            return null;
        }

        $elapsedTime = now()->diffInSeconds($this->actual_start);
        $questionTime = $elapsedTime % 30; // 30 secondes par question
        
        return max(0, 30 - $questionTime);
    }

    /**
     * Get participant statistics.
     */
    public function getParticipantStats(): array
    {
        $participants = $this->participants ?? [];
        
        return [
            'total' => count($participants),
            'active' => collect($participants)->filter(fn($p) => 
                now()->diffInSeconds($p['joined_at']) < 300 // 5 minutes
            )->count(),
            'average_score' => collect($participants)->avg('score'),
            'highest_score' => collect($participants)->max('score')
        ];
    }
} 