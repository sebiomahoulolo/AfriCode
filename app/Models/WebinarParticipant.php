<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'user_id',
        'is_host',
        'is_presenter',
        'joined_at',
        'left_at'
    ];

    protected $casts = [
        'is_host' => 'boolean',
        'is_presenter' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCurrentlyPresent()
    {
        return $this->joined_at && !$this->left_at;
    }

    public function getDuration()
    {
        if (!$this->joined_at) {
            return 0;
        }

        $end = $this->left_at ?? now();
        return $end->diffInSeconds($this->joined_at);
    }
} 