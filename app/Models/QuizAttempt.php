<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'enrollment_id',
        'started_at',
        'completed_at',
        'score',
        'passed',
        'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'float',
        'passed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    
    public function answers()
    {
        return $this->hasMany(UserQuizAnswer::class);
    }
    
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
    
    public function isAbandoned()
    {
        return $this->status === 'abandoned';
    }
    
    public function calculateScore()
    {
        $totalPoints = $this->quiz->getTotalPoints();
        if ($totalPoints === 0) {
            return 0;
        }
        
        $earnedPoints = $this->answers()
            ->whereHas('question')
            ->whereNotNull('is_correct')
            ->join('questions', 'questions.id', '=', 'user_quiz_answers.question_id')
            ->where('user_quiz_answers.is_correct', true)
            ->sum('questions.points');
            
        $score = ($earnedPoints / $totalPoints) * 100;
        return round($score, 2);
    }
    
    public function markAsCompleted()
    {
        $this->completed_at = now();
        $this->status = 'completed';
        $this->score = $this->calculateScore();
        $this->passed = $this->score >= $this->quiz->passing_score;
        return $this->save();
    }
    
    public function getRemainingTime()
    {
        if (!$this->quiz->time_limit_minutes) {
            return null;
        }
        
        $endTime = $this->started_at->addMinutes($this->quiz->time_limit_minutes);
        if (now()->greaterThan($endTime)) {
            return 0;
        }
        
        return now()->diffInSeconds($endTime);
    }
    
    public function hasTimedOut()
    {
        if (!$this->quiz->time_limit_minutes) {
            return false;
        }
        
        $endTime = $this->started_at->addMinutes($this->quiz->time_limit_minutes);
        return now()->greaterThan($endTime);
    }

    // Accesseur pour obtenir le score en pourcentage
    public function getScorePercentageAttribute()
    {
        return $this->score;
    }

    // Accesseur pour obtenir le nombre total de questions
    public function getTotalQuestionsAttribute()
    {
        return $this->quiz->questions->count();
    }

    // Accesseur pour obtenir le nombre de réponses correctes
    public function getCorrectAnswersAttribute()
    {
        return $this->answers()->where('is_correct', true)->distinct('question_id')->count();
    }

    // Accesseur pour obtenir le temps pris
    public function getTimeTakenAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInSeconds($this->completed_at);
        }
        return 0;
    }
}
