<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'type',
        'path_or_url'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    
    public function isFile()
    {
        return $this->type === 'file';
    }
    
    public function isLink()
    {
        return $this->type === 'link';
    }
}
