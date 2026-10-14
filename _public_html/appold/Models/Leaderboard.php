<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'competition_id',
        'start_date',
        'end_date',
        'is_active',
        'settings'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array'
    ];

    // Relations
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function userScores()
    {
        return $this->hasMany(UserScore::class);
    }

    public function topUsers($limit = 10)
    {
        return $this->userScores()
            ->with('user')
            ->orderBy('score', 'desc')
            ->orderBy('rank', 'asc')
            ->limit($limit)
            ->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->where('type', 'global');
    }

    public function scopeWeekly($query)
    {
        return $query->where('type', 'weekly');
    }

    public function scopeMonthly($query)
    {
        return $query->where('type', 'monthly');
    }

    // Méthodes utilitaires
    public function updateRanks()
    {
        $scores = $this->userScores()->orderBy('score', 'desc')->get();
        
        foreach ($scores as $index => $score) {
            $score->update(['rank' => $index + 1]);
        }
    }

    public function getUserRank($userId)
    {
        return $this->userScores()
            ->where('user_id', $userId)
            ->value('rank');
    }

    public function getUserScore($userId)
    {
        return $this->userScores()
            ->where('user_id', $userId)
            ->first();
    }
}
