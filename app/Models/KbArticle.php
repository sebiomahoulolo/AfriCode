<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KbArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'tags',
        'is_featured',
        'is_published',
        'published_at',
        'views_count',
        'helpful_count',
        'not_helpful_count'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(KbCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(KbArticleComment::class, 'article_id');
    }

    public function feedback()
    {
        return $this->hasMany(KbArticleFeedback::class, 'article_id');
    }

    public function revisions()
    {
        return $this->hasMany(KbArticleRevision::class, 'article_id');
    }

    public function relatedArticles()
    {
        return $this->belongsToMany(KbArticle::class, 'kb_article_relations', 'article_id', 'related_article_id')
            ->withPivot('relation_type')
            ->withTimestamps();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function markAsHelpful()
    {
        $this->increment('helpful_count');
    }

    public function markAsNotHelpful()
    {
        $this->increment('not_helpful_count');
    }
}
