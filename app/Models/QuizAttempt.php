<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'started_at',
        'submitted_at',
        'score',
        'answers',
        'anti_cheat_data'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
        'anti_cheat_data' => 'array'
    ];

    /**
     * Get the user that made the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz that was attempted.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the anti-cheat violations for this attempt.
     */
    public function violations(): HasMany
    {
        return $this->hasMany(AntiCheatViolation::class);
    }

    /**
     * Check if the attempt is still in progress.
     */
    public function isInProgress(): bool
    {
        return !$this->submitted_at;
    }

    /**
     * Calculate the score for the attempt.
     */
    public function calculateScore(): int
    {
        if (!$this->submitted_at) {
            return 0;
        }

        $score = 0;
        foreach ($this->answers as $questionId => $answer) {
            $question = $this->quiz->questions()->find($questionId);
            if ($question && $question->isCorrect($answer)) {
                $score += $question->points;
            }
        }

        $this->score = $score;
        $this->save();

        return $score;
    }

    /**
     * Record anti-cheat data.
     */
    public function recordAntiCheatData(array $data): void
    {
        $currentData = $this->anti_cheat_data ?? [];
        $this->anti_cheat_data = array_merge($currentData, $data);
        $this->save();
    }

    /**
     * Check for potential cheating.
     */
    public function checkForCheating(): array
    {
        $violations = [];
        $rules = AntiCheatRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($this->violatesRule($rule)) {
                $violations[] = $this->recordViolation($rule);
            }
        }

        return $violations;
    }

    /**
     * Check if the attempt violates a specific rule.
     */
    private function violatesRule(AntiCheatRule $rule): bool
    {
        return match($rule->type) {
            'tab_switch' => $this->checkTabSwitchViolation($rule),
            'copy_paste' => $this->checkCopyPasteViolation($rule),
            'time_limit' => $this->checkTimeLimitViolation($rule),
            default => false
        };
    }

    /**
     * Record a violation.
     */
    private function recordViolation(AntiCheatRule $rule): AntiCheatViolation
    {
        return $this->violations()->create([
            'anti_cheat_rule_id' => $rule->id,
            'violation_type' => $rule->type,
            'violation_data' => $this->anti_cheat_data[$rule->type] ?? [],
            'detected_at' => now()
        ]);
    }

    /**
     * Check for tab switch violations.
     */
    private function checkTabSwitchViolation(AntiCheatRule $rule): bool
    {
        $data = $this->anti_cheat_data['tab_switches'] ?? [];
        $maxSwitches = $rule->parameters['max_switches'] ?? 3;
        
        return count($data) > $maxSwitches;
    }

    /**
     * Check for copy-paste violations.
     */
    private function checkCopyPasteViolation(AntiCheatRule $rule): bool
    {
        $data = $this->anti_cheat_data['copy_paste_events'] ?? [];
        $maxEvents = $rule->parameters['max_events'] ?? 0;
        
        return count($data) > $maxEvents;
    }

    /**
     * Check for time limit violations.
     */
    private function checkTimeLimitViolation(AntiCheatRule $rule): bool
    {
        if (!$this->submitted_at) {
            return false;
        }

        $timeLimit = $rule->parameters['time_limit'] ?? 0;
        $duration = $this->submitted_at->diffInSeconds($this->started_at);
        
        return $duration > $timeLimit;
    }
}
