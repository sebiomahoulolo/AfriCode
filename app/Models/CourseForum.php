<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseForum extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'is_closed'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_closed' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }
    
    public function lastActivity()
    {
        $latestPost = $this->posts()->latest()->first();
        if ($latestPost) {
            return $latestPost->created_at;
        }
        return $this->created_at;
    }
    
    public function getTotalRepliesCount()
    {
        return $this->posts()->count();
    }
}
