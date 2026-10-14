<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'metadata',
        'points_earned',
    ];

    protected $casts = [
        'metadata' => 'array',
        'points_earned' => 'integer',
    ];

    /**
     * L'utilisateur associé à cette activité.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enregistre une nouvelle activité.
     */
    public static function log(
        User $user,
        string $type,
        string $description,
        array $metadata = [],
        int $points = 0
    ): self {
        $activity = self::create([
            'user_id' => $user->id,
            'type' => $type,
            'description' => $description,
            'metadata' => $metadata,
            'points_earned' => $points,
        ]);

        // Si des points sont gagnés, mettre à jour la progression de l'utilisateur
        if ($points > 0) {
            $user->progress->addExperience($points);
        }

        return $activity;
    }

    /**
     * Récupère les activités récentes d'un utilisateur.
     */
    public static function getRecentActivities(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Récupère les activités par type.
     */
    public static function getActivitiesByType(User $user, string $type): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $user->id)
            ->where('type', $type)
            ->latest()
            ->get();
    }

    /**
     * Récupère le total des points gagnés par type d'activité.
     */
    public static function getPointsByActivityType(User $user): array
    {
        return self::where('user_id', $user->id)
            ->selectRaw('type, SUM(points_earned) as total_points')
            ->groupBy('type')
            ->pluck('total_points', 'type')
            ->toArray();
    }

    /**
     * Récupère les statistiques d'activité d'un utilisateur.
     */
    public static function getUserStats(User $user): array
    {
        return [
            'total_activities' => self::where('user_id', $user->id)->count(),
            'total_points_earned' => self::where('user_id', $user->id)->sum('points_earned'),
            'activity_types' => self::where('user_id', $user->id)
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'recent_activity' => self::getRecentActivities($user, 5),
        ];
    }

    /**
     * Récupère les activités d'un utilisateur pour une période donnée.
     */
    public static function getActivitiesForPeriod(
        User $user,
        \Carbon\Carbon $startDate,
        \Carbon\Carbon $endDate
    ): \Illuminate\Database\Eloquent\Collection {
        return self::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();
    }

    /**
     * Récupère les activités liées à un cours spécifique.
     */
    public static function getCourseActivities(User $user, int $courseId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $user->id)
            ->where('type', 'like', 'course_%')
            ->whereJsonContains('metadata->course_id', $courseId)
            ->latest()
            ->get();
    }

    /**
     * Récupère les activités liées à un quiz spécifique.
     */
    public static function getQuizActivities(User $user, int $quizId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $user->id)
            ->where('type', 'like', 'quiz_%')
            ->whereJsonContains('metadata->quiz_id', $quizId)
            ->latest()
            ->get();
    }
} 