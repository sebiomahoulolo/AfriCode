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
        'module_id',
        'course_id',
        'quiz_type', // 'module_end' ou 'course_final'
        'passing_score',
        'time_limit_minutes',
        'is_required',
        'order',
        'max_attempts'
    ];

    protected $casts = [
        'passing_score' => 'integer',
        'time_limit_minutes' => 'integer',
        'is_required' => 'boolean',
        'order' => 'integer',
        'max_attempts' => 'integer'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    // Relations directes au lieu de morphTo
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
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

    public function isPassedByUser($userId)
    {
        $bestAttempt = $this->bestAttemptByUser($userId);
        return $bestAttempt && $bestAttempt->score >= $this->passing_score;
    }

    public function isModuleQuiz()
    {
        return $this->quiz_type === 'module_end';
    }

    public function isCourseQuiz()
    {
        return $this->quiz_type === 'course_final';
    }

    // Vérifier si l'utilisateur peut accéder à ce quiz
    public function canBeAccessedByUser($userId)
    {
        if ($this->isModuleQuiz()) {
            // Pour un quiz de module, toutes les leçons du module doivent être complétées
            $module = $this->module;
            
            if (!$module) return false;
            
            return $module->allLessonsCompletedByUser($userId);
        }
        
        if ($this->isCourseQuiz()) {
            // Pour un quiz de cours, tous les quiz de modules requis doivent être réussis
            $course = $this->course;
            if (!$course) return false;

            foreach ($course->modules as $module) {
                if ($module->quiz && $module->quiz->is_required) {
                    if (!$module->quiz->isPassedByUser($userId)) {
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        return false;
    }

    public function canBeAttemptedByUser($userId)
    {
        // Si le quiz a déjà été réussi, vérifier si c'était le dernier essai réussi
        if ($this->isPassedByUser($userId)) {
            $attempts = $this->attempts()
                ->where('user_id', $userId)
                ->orderBy('completed_at', 'desc')
                ->get();
            
            $lastAttempt = $attempts->first();
            if ($lastAttempt && $lastAttempt->passed) {
                return false; // Ne peut plus retenter si le dernier essai était réussi
            }
        }

        // Si max_attempts est 0, pas de limite
        if ($this->max_attempts === 0) {
            return true;
        }

        // Compter les tentatives de l'utilisateur
        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return $attemptCount < $this->max_attempts;
    }

    public function getRemainingAttempts($userId)
    {
        if ($this->max_attempts === 0) {
            return -1; // -1 indique un nombre illimité de tentatives
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return max(0, $this->max_attempts - $attemptCount);
    }
}
