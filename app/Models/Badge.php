<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'type',
        'requirements',
        'points_reward',
        'is_secret'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_secret' => 'boolean',
        'points_reward' => 'integer'
    ];

    /**
     * Les utilisateurs qui ont obtenu ce badge.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Vérifie si un utilisateur peut obtenir ce badge.
     */
    public function canBeEarnedBy(User $user): bool
    {
        // Vérifier si l'utilisateur a déjà ce badge
        if ($this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Vérifier si l'utilisateur a assez de points
        $userProgress = $user->progress;
        if (!$userProgress || $userProgress->total_points < $this->points_reward) {
            return false;
        }

        // Vérifier les conditions spécifiques selon le type de badge
        return match($this->type) {
            'course_completion' => $this->checkCourseCompletion($user),
            'quiz_master' => $this->checkQuizMaster($user),
            'social_butterfly' => $this->checkSocialButterfly($user),
            default => true,
        };
    }

    /**
     * Vérifie les conditions pour le badge de complétion de cours.
     */
    private function checkCourseCompletion(User $user): bool
    {
        $completedCourses = $user->enrollments()
            ->where('status', 'completed')
            ->count();

        return $completedCourses >= $this->level;
    }

    /**
     * Vérifie les conditions pour le badge de maître des quiz.
     */
    private function checkQuizMaster(User $user): bool
    {
        $quizAttempts = $user->quizAttempts()
            ->where('score', '>=', 80)
            ->count();

        return $quizAttempts >= $this->level;
    }

    /**
     * Vérifie les conditions pour le badge de papillon social.
     */
    private function checkSocialButterfly(User $user): bool
    {
        $socialActivities = $user->activities()
            ->whereIn('type', ['post_created', 'comment_created', 'like_given'])
            ->count();

        return $socialActivities >= $this->level;
    }

    /**
     * Attribue le badge à un utilisateur.
     */
    public function awardTo(User $user): void
    {
        if ($this->canBeEarnedBy($user)) {
            $this->users()->attach($user->id, ['earned_at' => now()]);
            
            // Add points reward
            if ($this->points_reward > 0) {
                $user->progress->addExperience($this->points_reward);
            }

            // Log the achievement
            $user->activities()->create([
                'type' => 'badge_earned',
                'description' => "Earned badge: {$this->name}",
                'metadata' => [
                    'badge_id' => $this->id,
                    'points_reward' => $this->points_reward
                ],
                'points_earned' => $this->points_reward
            ]);
        }
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
} 