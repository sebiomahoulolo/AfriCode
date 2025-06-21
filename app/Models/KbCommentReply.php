<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KbCommentReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'comment_id',
        'user_id',
        'content',
        'is_staff_reply'
    ];

    protected $casts = [
        'is_staff_reply' => 'boolean'
    ];

    public function comment()
    {
        return $this->belongsTo(KbArticleComment::class, 'comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeStaffReplies($query)
    {
        return $query->where('is_staff_reply', true);
    }

    public function scopeUserReplies($query)
    {
        return $query->where('is_staff_reply', false);
    }
}
