<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webinar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'host_id',
        'course_id',
        'start_time',
        'end_time',
        'meeting_link',
        'meeting_id',
        'meeting_password',
        'max_participants',
        'is_recorded',
        'recording_url',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_recorded' => 'boolean',
        'max_participants' => 'integer',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function participants()
    {
        return $this->hasMany(WebinarParticipant::class);
    }

    public function isLive()
    {
        return $this->status === 'live';
    }

    public function isUpcoming()
    {
        return $this->status === 'scheduled' && $this->start_time > now();
    }

    public function isPast()
    {
        return $this->status === 'completed' || $this->end_time < now();
    }
} 