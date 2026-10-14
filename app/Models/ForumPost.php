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

    public function mentions()
    {
        return $this->morphMany(Mention::class, 'mentionable');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function upvotes()
    {
        return $this->votes()->where('type', 'upvote');
    }

    public function downvotes()
    {
        return $this->votes()->where('type', 'downvote');
    }

    public function getScoreAttribute()
    {
        return Vote::getScore($this);
    }

    public function hasUserVoted($user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function getUserVote($user)
    {
        return $this->votes()->where('user_id', $user->id)->first();
    }

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

    public function notifications()
    {
        return $this->hasMany(ForumNotification::class);
    }

    /**
     * Notifier l'auteur du post parent quand une réponse est ajoutée
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            // Notifier pour les réponses
            if ($post->isReply()) {
                $parentPost = $post->parentPost;
                if ($parentPost && $parentPost->user_id !== $post->user_id) {
                    ForumNotification::create([
                        'user_id' => $parentPost->user_id,
                        'forum_post_id' => $parentPost->id,
                        'reply_id' => $post->id,
                        'type' => 'reply'
                    ]);
                }
            }

            // Traiter les mentions
            Mention::processMentions($post->content, $post, $post->user);
        });
    }
}
