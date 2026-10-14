<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KbArticleFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'type',
        'comment'
    ];

    public function article()
    {
        return $this->belongsTo(KbArticle::class, 'article_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeHelpful($query)
    {
        return $query->where('type', 'helpful');
    }

    public function scopeNotHelpful($query)
    {
        return $query->where('type', 'not_helpful');
    }

    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment');
    }
}
