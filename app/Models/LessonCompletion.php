<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'enrollment_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        // When a lesson is marked as completed, update the enrollment progress
        static::created(function ($completion) {
            if ($completion->enrollment) {
                $completion->enrollment->updateProgress();
            }
        });
        
        // When a lesson completion is deleted, update the enrollment progress
        static::deleted(function ($completion) {
            if ($completion->enrollment) {
                $completion->enrollment->updateProgress();
            }
        });
    }
}
