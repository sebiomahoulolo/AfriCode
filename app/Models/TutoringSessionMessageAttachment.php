<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringSessionMessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'filename',
        'path',
        'size',
        'mime_type'
    ];

    public function message()
    {
        return $this->belongsTo(TutoringSessionMessage::class, 'message_id');
    }
}
