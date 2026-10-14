<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringSessionResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'title',
        'type',
        'url',
        'file_path',
        'description'
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class);
    }
}
