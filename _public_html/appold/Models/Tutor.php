<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'bio',
        'expertise_areas',
        'languages',
        'hourly_rate',
        'is_available',
        'availability_schedule',
        'rating',
        'total_sessions'
    ];

    protected $casts = [
        'expertise_areas' => 'array',
        'languages' => 'array',
        'availability_schedule' => 'array',
        'is_available' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'rating' => 'integer',
        'total_sessions' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sessions()
    {
        return $this->hasMany(TutoringSession::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByExpertise($query, $expertise)
    {
        return $query->whereJsonContains('expertise_areas', $expertise);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->whereJsonContains('languages', $language);
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function updateRating()
    {
        $averageRating = $this->sessions()
            ->whereHas('feedback')
            ->with('feedback')
            ->get()
            ->pluck('feedback.rating')
            ->avg();

        $this->update(['rating' => round($averageRating)]);
    }

    public function incrementSessions()
    {
        $this->increment('total_sessions');
    }

    public function setAvailability($isAvailable)
    {
        $this->update(['is_available' => $isAvailable]);
    }

    public function updateSchedule($schedule)
    {
        $this->update(['availability_schedule' => $schedule]);
    }
}
