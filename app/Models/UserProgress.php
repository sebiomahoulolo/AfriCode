<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'total_points',
        'current_level',
        'experience_points',
        'achievements',
    ];

    protected $casts = [
        'achievements' => 'array',
    ];

    /**
     * L'utilisateur associé à cette progression.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ajoute des points d'expérience à l'utilisateur.
     */
    public function addExperience(int $points): void
    {
        $this->experience_points += $points;
        $this->total_points += $points;

        // Vérifier si l'utilisateur peut passer au niveau suivant
        $this->checkLevelUp();

        $this->save();
    }

    /**
     * Vérifie si l'utilisateur peut passer au niveau suivant.
     */
    private function checkLevelUp(): void
    {
        $nextLevelThreshold = $this->calculateNextLevelThreshold();

        if ($this->experience_points >= $nextLevelThreshold) {
            $this->current_level++;
            
            // Enregistrer l'activité de passage de niveau
            $this->user->activities()->create([
                'type' => 'level_up',
                'description' => "A atteint le niveau {$this->current_level}",
                'metadata' => [
                    'previous_level' => $this->current_level - 1,
                    'new_level' => $this->current_level,
                    'experience_gained' => $this->experience_points,
                ],
                'points_earned' => 0,
            ]);

            // Vérifier les badges liés au niveau
            $this->checkLevelBasedBadges();
        }
    }

    /**
     * Calcule le seuil d'expérience nécessaire pour le prochain niveau.
     */
    private function calculateNextLevelThreshold(): int
    {
        // Formule : 100 * (niveau actuel)²
        return 100 * pow($this->current_level, 2);
    }

    /**
     * Vérifie si l'utilisateur peut obtenir des badges basés sur son niveau.
     */
    private function checkLevelBasedBadges(): void
    {
        $levelBadges = Badge::where('type', 'level')
            ->where('level', $this->current_level)
            ->get();

        foreach ($levelBadges as $badge) {
            $badge->awardTo($this->user);
        }
    }

    /**
     * Ajoute une réalisation à l'utilisateur.
     */
    public function addAchievement(string $achievement, array $metadata = []): void
    {
        $achievements = $this->achievements ?? [];
        $achievements[$achievement] = [
            'earned_at' => now(),
            'metadata' => $metadata,
        ];

        $this->achievements = $achievements;
        $this->save();

        // Enregistrer l'activité
        $this->user->activities()->create([
            'type' => 'achievement_earned',
            'description' => "A obtenu la réalisation : {$achievement}",
            'metadata' => $metadata,
            'points_earned' => 0,
        ]);
    }

    /**
     * Vérifie si l'utilisateur a une réalisation spécifique.
     */
    public function hasAchievement(string $achievement): bool
    {
        return isset($this->achievements[$achievement]);
    }

    /**
     * Récupère toutes les réalisations de l'utilisateur.
     */
    public function getAchievements(): array
    {
        return $this->achievements ?? [];
    }

    /**
     * Calcule le pourcentage de progression vers le prochain niveau.
     */
    public function getLevelProgressPercentage(): float
    {
        $currentLevelThreshold = $this->calculateNextLevelThreshold();
        $previousLevelThreshold = 100 * pow($this->current_level - 1, 2);
        $levelRange = $currentLevelThreshold - $previousLevelThreshold;
        $currentProgress = $this->experience_points - $previousLevelThreshold;

        return min(100, max(0, ($currentProgress / $levelRange) * 100));
    }
} 