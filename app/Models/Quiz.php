<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'related_id',
        'related_type',
        'passing_score',
        'time_limit_minutes'
    ];

    protected $casts = [
        'passing_score' => 'integer',
        'time_limit_minutes' => 'integer'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function latestAttemptByUser($userId)
    {
        return $this->attempts()->where('user_id', $userId)->latest()->first();
    }

    public function bestAttemptByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->orderByDesc('score')
            ->first();
    }

    public function getTotalPoints()
    {
        return $this->questions->sum('points');
    }
}
