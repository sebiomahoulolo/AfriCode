<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosition extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'places_count',
        'missions',
        'profil',
        'dossier_fournir',
        'is_active',
        'deadline',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline' => 'date',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(RecruitmentQuestion::class)->orderBy('sort_order');
    }

    public function isOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        if ($this->deadline && $this->deadline->isPast()) {
            return false;
        }
        return true;
    }
}
