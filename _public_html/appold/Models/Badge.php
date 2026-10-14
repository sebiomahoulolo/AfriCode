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
        'color',
        'is_locked',
        'unlock_order',
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
        $canEarn = match($this->type) {
            'course_completion' => $this->checkCourseCompletion($user),
            'quiz_master' => $this->checkQuizMaster($user),
            'social_butterfly' => $this->checkSocialButterfly($user),
            default => true,
        };

        if ($canEarn) {
            $user->notify(new \App\Notifications\BadgeUnlocked());
        }

        return $canEarn;
    }

    /**
     * Vérifie les conditions pour le badge de complétion de cours.
     */
    private function checkCourseCompletion(User $user): bool
    {
        $completedCourses = $user->enrollments()
            ->whereNotNull('completed_at')
            ->count();

        return $completedCourses >= ($this->requirements['courses_completed'] ?? 1);
    }

    /**
     * Vérifie les conditions pour le badge de maître des quiz.
     */
    private function checkQuizMaster(User $user): bool
    {
        $quizAttempts = $user->quizAttempts()
            ->where('score', '>=', 80)
            ->count();

        return $quizAttempts >= ($this->requirements['quizzes_passed'] ?? 5);
    }

    /**
     * Vérifie les conditions pour le badge de papillon social.
     */
    private function checkSocialButterfly(User $user): bool
    {
        $socialActivities = $user->activities()
            ->whereIn('type', ['post_created', 'comment_created', 'like_given'])
            ->count();

        return $socialActivities >= ($this->requirements['social_activities'] ?? 10);
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

        if (!$type || $value === null) {
            return false;
        }

        try {
            return match ($type) {
                'level' => ($user->progress?->current_level ?? 1) >= $value,
                'points' => ($user->progress?->total_points ?? 0) >= $value,
                'courses_completed' => $user->enrollments()->whereNotNull('completed_at')->count() >= $value,
                'quizzes_passed' => $user->quizAttempts()->where('score', '>=', 70)->count() >= $value,
                'perfect_quizzes' => $user->quizAttempts()->where('score', 100)->count() >= $value,
                'streak_days' => $this->calculateUserStreak($user) >= $value,
                'social_activities' => $user->activities()->whereIn('type', ['post_created', 'comment_created', 'like_given'])->count() >= $value,
                'certifications_earned' => $user->certifications()->count() >= $value,
                'forum_posts' => $user->forumPosts()->count() >= $value,
                default => false,
            };
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur lors de l'évaluation du badge {$this->id} pour l'utilisateur {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcule la série de jours consécutifs d'activité de l'utilisateur
     */
    private function calculateUserStreak(User $user): int
    {
        $activities = $user->activities()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });

        $streak = 0;
        $currentDate = now()->format('Y-m-d');

        foreach ($activities as $date => $dayActivities) {
            if ($date === $currentDate || $date === now()->subDays($streak)->format('Y-m-d')) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
} 