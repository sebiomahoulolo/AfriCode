<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ProgressionMilestone;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'completed_at',
        'progress_percentage'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function lessonCompletions()
    {
        return $this->hasMany(LessonCompletion::class);
    }
    
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
    
    public function certification()
    {
        return $this->hasOne(Certification::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function isCompleted()
    {
        return $this->completed_at !== null;
    }
    
    public function calculateProgressPercentage()
    {
        $course = $this->course;
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($course->modules as $module) {
            $moduleLessons = $module->lessons()->count();
            $totalLessons += $moduleLessons;
            $completedLessons += $this->lessonCompletions()
                ->whereIn('lesson_id', $module->lessons()->pluck('id'))
                ->count();
        }
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        return round(($completedLessons / $totalLessons) * 100);
    }
    
    public function updateProgress()
    {
        $course = $this->course;
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($course->modules as $module) {
            $moduleLessons = $module->lessons()->count();
            $totalLessons += $moduleLessons;
            $completedLessons += $this->lessonCompletions()
                ->whereIn('lesson_id', $module->lessons()->pluck('id'))
                ->count();
        }
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        $progress = round(($completedLessons / $totalLessons) * 100);
        $this->progress_percentage = $progress;
        $this->save();

        // Notifier l'utilisateur s'il atteint 50% de progression
        if ($progress === 50) {
            $this->user->notify(new ProgressionMilestone());
        }

        return $progress;
    }
}
