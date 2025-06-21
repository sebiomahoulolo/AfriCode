<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KbArticleComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'article_id',
        'user_id',
        'content',
        'is_resolved',
        'is_private'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'is_private' => 'boolean'
    ];

    public function article()
    {
        return $this->belongsTo(KbArticle::class, 'article_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(KbCommentReply::class, 'comment_id');
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    public function markAsResolved()
    {
        $this->update(['is_resolved' => true]);
    }

    public function markAsUnresolved()
    {
        $this->update(['is_resolved' => false]);
    }
}
