<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content_type',
        'video_url',
        'text_content',
        'pdf_path',
        'external_url',
        'duration_minutes',
        'order',
        'is_previewable'
    ];

    protected $casts = [
        'is_previewable' => 'boolean',
        'duration_minutes' => 'integer',
        'order' => 'integer'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function isCompletedBy($userId)
    {
        return $this->completions()->where('user_id', $userId)->exists();
    }

    public function getCourse()
    {
        return $this->module->course;
    }
}
