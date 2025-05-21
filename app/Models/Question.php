<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'text',
        'type',
        'points',
        'order',
        'feedback_correct',
        'feedback_incorrect'
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('order');
    }
    
    public function userQuizAnswers()
    {
        return $this->hasMany(UserQuizAnswer::class);
    }
    
    public function correctAnswers()
    {
        return $this->answers()->where('is_correct', true)->get();
    }
    
    public function isMultipleChoice()
    {
        return $this->type === 'multiple_choice';
    }
    
    public function isSingleChoice()
    {
        return $this->type === 'single_choice';
    }
    
    public function isTrueFalse()
    {
        return $this->type === 'true_false';
    }
    
    public function isOpenText()
    {
        return $this->type === 'open_text';
    }
}
