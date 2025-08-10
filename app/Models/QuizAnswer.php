<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $table = 'user_quiz_answers';
    
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'answer_id',
        'open_text_answer',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}