<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormateurCommunityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'event_type',
        'title',
        'description',
        'agenda',
        'scheduled_at',
        'ended_at',
        'duration_minutes',
        'meeting_url',
        'recording_url',
        'attendance_count',
        'participants_ids',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
        'participants_ids' => 'array',
    ];

    // Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
                    ->where('status', 'scheduled');
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', now())
                    ->where('status', 'completed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    // Méthodes
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);
    }

    public function addParticipant($userId)
    {
        $participants = $this->participants_ids ?? [];
        if (!in_array($userId, $participants)) {
            $participants[] = $userId;
            $this->update([
                'participants_ids' => $participants,
                'attendance_count' => count($participants),
            ]);
        }
    }

    public function getEventTypeLabelAttribute()
    {
        return match($this->event_type) {
            'meeting' => 'Réunion',
            'webinar' => 'Webinaire',
            'workshop' => 'Atelier',
            'announcement' => 'Annonce',
            default => 'Événement',
        };
    }
}

