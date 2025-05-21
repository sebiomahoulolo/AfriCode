<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'target_type',
        'target_id',
        'published_at',
        'expires_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        })->where(function ($q) {
            $q->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        });
    }
    
    public function isGlobal()
    {
        return $this->target_type === 'global';
    }
    
    public function isCourseSpecific()
    {
        return $this->target_type === 'course';
    }
    
    public function isRoleSpecific()
    {
        return $this->target_type === 'user_role';
    }
    
    public function targetCourse()
    {
        if (!$this->isCourseSpecific()) {
            return null;
        }
        
        return Course::find($this->target_id);
    }
    
    public function isPublished()
    {
        return $this->published_at !== null && $this->published_at->lte(now());
    }
    
    public function isExpired()
    {
        return $this->expires_at !== null && $this->expires_at->lt(now());
    }
}
