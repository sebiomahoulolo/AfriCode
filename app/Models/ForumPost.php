<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_forum_id',
        'user_id',
        'parent_post_id',
        'content'
    ];

    public function courseForum()
    {
        return $this->belongsTo(CourseForum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function parentPost()
    {
        return $this->belongsTo(ForumPost::class, 'parent_post_id');
    }
    
    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_post_id');
    }
    
    public function isReply()
    {
        return $this->parent_post_id !== null;
    }
}
