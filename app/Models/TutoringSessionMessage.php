<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringSessionMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'message',
        'type',
        'is_system_message'
    ];

    protected $casts = [
        'is_system_message' => 'boolean'
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(TutoringSessionMessageAttachment::class, 'message_id');
    }
}
