<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Challenge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'difficulty',
        'time_limit_minutes',
        'hints',
        'solution_template',
        'is_featured',
        'requirements',
        'rewards',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'requirements' => 'array',
        'rewards' => 'array',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('progress', 'is_completed', 'completed_at')
            ->withTimestamps();
    }

    public function isActive(): bool
    {
        return $this->is_active && 
               now()->between($this->start_date, $this->end_date);
    }

    public function enrollUser(User $user): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        $this->users()->attach($user->id, [
            'progress' => [],
            'is_completed' => false
        ]);

        // Log the challenge enrollment
        $user->activities()->create([
            'type' => 'challenge_enrolled',
            'description' => "Enrolled in challenge: {$this->name}",
            'metadata' => [
                'challenge_id' => $this->id,
                'type' => $this->type
            ]
        ]);

        return true;
    }

    public function updateProgress(User $user, array $progress): bool
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;

        if (!$pivot || $pivot->is_completed) {
            return false;
        }

        $currentProgress = $pivot->progress ?? [];
        $newProgress = array_merge($currentProgress, $progress);

        $pivot->update(['progress' => $newProgress]);

        if ($this->checkCompletion($user)) {
            $this->completeChallenge($user);
        }

        return true;
    }

    protected function checkCompletion(User $user): bool
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;
        if (!$pivot) return false;

        $progress = $pivot->progress ?? [];

        foreach ($this->requirements as $requirement) {
            $type = $requirement['type'] ?? null;
            $value = $requirement['value'] ?? null;
            $current = $progress[$type] ?? 0;

            if ($current < $value) {
                return false;
            }
        }

        return true;
    }

    protected function completeChallenge(User $user): void
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;
        if (!$pivot) return;

        $pivot->update([
            'is_completed' => true,
            'completed_at' => now()
        ]);

        // Award rewards
        foreach ($this->rewards as $reward) {
            $this->awardReward($user, $reward);
        }

        // Log the challenge completion
        $user->activities()->create([
            'type' => 'challenge_completed',
            'description' => "Completed challenge: {$this->name}",
            'metadata' => [
                'challenge_id' => $this->id,
                'type' => $this->type,
                'rewards' => $this->rewards
            ]
        ]);
    }

    protected function awardReward(User $user, array $reward): void
    {
        $type = $reward['type'] ?? null;
        $value = $reward['value'] ?? 0;

        match ($type) {
            'points' => $user->progress->addExperience($value),
            'badge' => Badge::find($value)?->awardTo($user),
            'reward' => Reward::find($value)?->claim($user),
            default => null,
        };
    }

    public function getLeaderboard(int $limit = 10): array
    {
        return $this->users()
            ->wherePivot('is_completed', true)
            ->orderByPivot('completed_at')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'user' => $user->name,
                    'completed_at' => $user->pivot->completed_at,
                    'time_taken' => $user->pivot->completed_at->diffInSeconds($this->start_date)
                ];
            })
            ->toArray();
    }
} 