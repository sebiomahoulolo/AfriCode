<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutoringResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id',
        'user_id',
        'title',
        'type',
        'file_path',
        'description'
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isDocument()
    {
        return $this->type === 'document';
    }

    public function isLink()
    {
        return $this->type === 'link';
    }

    public function isCode()
    {
        return $this->type === 'code';
    }

    public function getFileUrl()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }
}
