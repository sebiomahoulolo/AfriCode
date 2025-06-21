<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reward extends Model
{
    protected $fillable = [
        'name',
        'description',
        'amount',
        'currency',
        'type',
        'requirements',
        'is_active'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
        'amount' => 'decimal:2'
    ];

    /**
     * Les utilisateurs qui ont obtenu cette récompense.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('claimed_at', 'status')
            ->withTimestamps();
    }

    public function checkRequirements(User $user): bool
    {
        if (empty($this->requirements)) {
            return true;
        }

        foreach ($this->requirements as $requirement) {
            if (!$this->evaluateRequirement($user, $requirement)) {
                return false;
            }
        }

        return true;
    }

    protected function evaluateRequirement(User $user, array $requirement): bool
    {
        $type = $requirement['type'] ?? null;
        $value = $requirement['value'] ?? null;

        if (!$type || !$value) {
            return false;
        }

        return match ($type) {
            'level' => $user->progress->current_level >= $value,
            'points' => $user->progress->total_points >= $value,
            'courses_completed' => $user->completedCourses()->count() >= $value,
            'quizzes_passed' => $user->passedQuizzes()->count() >= $value,
            'perfect_quizzes' => $user->perfectQuizzes()->count() >= $value,
            'streak_days' => $user->currentStreak() >= $value,
            default => false,
        };
    }

    public function claim(User $user): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->checkRequirements($user)) {
            return false;
        }

        if ($this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        $this->users()->attach($user->id, [
            'claimed_at' => now(),
            'status' => 'pending'
        ]);

        // Log the reward claim
        $user->activities()->create([
            'type' => 'reward_claimed',
            'description' => "Claimed reward: {$this->name}",
            'metadata' => [
                'reward_id' => $this->id,
                'amount' => $this->amount,
                'currency' => $this->currency
            ]
        ]);

        return true;
    }

    public function processReward(User $user): bool
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;

        if (!$pivot || $pivot->status !== 'pending') {
            return false;
        }

        // Here you would implement the actual payment processing
        // For example, using a payment gateway or internal wallet system

        $pivot->update([
            'status' => 'completed'
        ]);

        // Log the reward completion
        $user->activities()->create([
            'type' => 'reward_completed',
            'description' => "Completed reward: {$this->name}",
            'metadata' => [
                'reward_id' => $this->id,
                'amount' => $this->amount,
                'currency' => $this->currency
            ]
        ]);

        return true;
    }
} 