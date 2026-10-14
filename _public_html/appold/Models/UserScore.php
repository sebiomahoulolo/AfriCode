<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leaderboard_id',
        'score',
        'rank',
        'score_breakdown',
        'last_updated'
    ];

    protected $casts = [
        'score_breakdown' => 'array',
        'last_updated' => 'datetime'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaderboard()
    {
        return $this->belongsTo(Leaderboard::class);
    }

    // Scopes
    public function scopeTopRanked($query, $limit = 10)
    {
        return $query->orderBy('score', 'desc')
                    ->orderBy('rank', 'asc')
                    ->limit($limit);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForLeaderboard($query, $leaderboardId)
    {
        return $query->where('leaderboard_id', $leaderboardId);
    }

    // Méthodes utilitaires
    public function updateScore($newScore, $breakdown = null)
    {
        $this->update([
            'score' => $newScore,
            'score_breakdown' => $breakdown ?? $this->score_breakdown,
            'last_updated' => now()
        ]);

        // Mettre à jour le classement
        $this->leaderboard->updateRanks();
    }

    public function addPoints($points, $reason = null)
    {
        $newScore = $this->score + $points;
        $breakdown = $this->score_breakdown ?? [];
        
        if ($reason) {
            $breakdown[$reason] = ($breakdown[$reason] ?? 0) + $points;
        }

        $this->updateScore($newScore, $breakdown);
    }

    public function getScoreBreakdown()
    {
        return $this->score_breakdown ?? [];
    }

    public function getFormattedScore()
    {
        return number_format($this->score);
    }
}
