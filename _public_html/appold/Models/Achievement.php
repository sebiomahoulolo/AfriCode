<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'points',
        'type', // 'course_completion', 'quiz_perfect', 'streak', etc.
        'requirement', // JSON field for specific requirements
        'badge_image'
    ];

    protected $casts = [
        'requirement' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
} 