<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'order',
        'is_published',
        'views_count',
        'helpful_count',
        'not_helpful_count'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer'
    ];

    public function tags()
    {
        return $this->belongsToMany(FaqTag::class, 'faq_article_tag');
    }

    public function relatedArticles()
    {
        return $this->belongsToMany(FaqArticle::class, 'faq_article_relations', 'article_id', 'related_article_id');
    }
} 