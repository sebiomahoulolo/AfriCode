<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'rules',
        'start_datetime',
        'end_datetime',
        'registration_deadline',
        'level_required',
        'organizer_id',
        'status',
        'cover_image_path',
        'max_participants',
        'entry_fee',
        'prizes',
        'judging_criteria',
        'is_featured',
        'views_count'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'registration_deadline' => 'datetime',
        'prizes' => 'array',
        'judging_criteria' => 'array',
        'is_featured' => 'boolean'
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    
    public function registrations()
    {
        return $this->hasMany(CompetitionRegistration::class);
    }
    
    public function submissions()
    {
        return $this->hasManyThrough(CompetitionSubmission::class, CompetitionRegistration::class, 'competition_id', 'registration_id');
    }
    
    public function participants()
    {
        return $this->belongsToMany(User::class, 'competition_registrations');
    }

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'competition_challenges')
                    ->withPivot(['points_reward', 'is_required', 'order'])
                    ->orderBy('pivot_order');
    }

    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($competition) {
            if (empty($competition->slug)) {
                $competition->slug = Str::slug($competition->title);
            }
        });
    }
    
    public function isUpcoming()
    {
        return now()->lt($this->start_datetime);
    }
    
    public function isInProgress()
    {
        return now()->between($this->start_datetime, $this->end_datetime);
    }
    
    public function isCompleted()
    {
        return now()->gt($this->end_datetime);
    }
    
    public function isRegistrationOpen()
    {
        return now()->lt($this->getRegistrationDeadline()) && $this->status === 'open_for_registration';
    }
    
    public function getRegistrationDeadline()
    {
        return $this->registration_deadline ?? $this->start_datetime;
    }
    
    public function getDurationInDays()
    {
        return $this->start_datetime->diffInDays($this->end_datetime);
    }
    
    public function getTimeRemaining()
    {
        if ($this->isCompleted()) {
            return 'Compétition terminée';
        }
        
        if ($this->isInProgress()) {
            return 'Se termine dans ' . now()->diffForHumans($this->end_datetime, ['parts' => 2]);
        }
        
        return 'Commence dans ' . now()->diffForHumans($this->start_datetime, ['parts' => 2]);
    }

    // Nouvelles méthodes
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function isFull()
    {
        if (!$this->max_participants) {
            return false;
        }
        return $this->registrations()->count() >= $this->max_participants;
    }

    public function getAvailableSpots()
    {
        if (!$this->max_participants) {
            return null;
        }
        return max(0, $this->max_participants - $this->registrations()->count());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['upcoming', 'open_for_registration', 'in_progress']);
    }

    public function testCases()
    {
        return $this->hasMany(CompetitionTestCase::class);
    }
}
