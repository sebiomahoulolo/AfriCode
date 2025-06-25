<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
    
    // Relation directe pour les quiz de fin de module
    public function quiz()
    {
        return $this->hasOne(Quiz::class)->where('quiz_type', 'module_end');
    }
    
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
    
    public function getCompletionPercentageAttribute($userId = null)
    {
        if (!$userId) {
            return 0;
        }
        
        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }
        
        $completedLessons = $this->lessons()
            ->whereHas('completions', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
            
        return round(($completedLessons / $totalLessons) * 100);
    }
    
    // Vérifier si toutes les leçons du module sont complétées
    public function allLessonsCompletedByUser($userId)
    {
        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) return true;
        
        $completedLessons = LessonCompletion::where('user_id', $userId)
            ->whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->count();
            
        return $completedLessons >= $totalLessons;
    }
    
    // Vérifier si le quiz du module est réussi
    public function quizPassedByUser($userId)
    {
        $quiz = $this->quiz;
        if (!$quiz) return true; // Pas de quiz = considéré comme réussi
        
        return $quiz->isPassedByUser($userId);
    }
    
    // Vérifier si le module est complètement terminé (leçons + quiz)
    public function isCompletedByUser($userId)
    {
        return $this->allLessonsCompletedByUser($userId) && $this->quizPassedByUser($userId);
    }
    
    // Vérifier si l'utilisateur peut accéder au quiz de ce module
    public function canUserAccessQuiz($userId)
    {
        return $this->allLessonsCompletedByUser($userId);
    }
}
