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
    
    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'related');
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
}
