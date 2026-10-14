<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswer extends Model
{
    use HasFactory;

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

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
    
    /**
     * Evaluate if this answer is correct based on the question type
     */
    public function evaluate()
    {
        $question = $this->question;
        
        if ($question->type === 'open_text') {
            // For open text questions, manual evaluation is needed
            // This could be done by an instructor/admin or using an AI service
            return null;
        }
        
        if ($question->type === 'true_false' || $question->type === 'single_choice') {
            // For true/false or single choice, check if the selected answer is correct
            $this->is_correct = optional($this->answer)->is_correct ?? false;
        }
        
        if ($question->type === 'multiple_choice') {
            // For multiple choice, check if all selected answers are correct
            $correctAnswers = $question->options()->where('is_correct', true)->pluck('id')->toArray();
            $userAnswers = [$this->answer_id];
            
            // Check if user selected exactly the correct answers
            $this->is_correct = count($correctAnswers) === count($userAnswers) && 
                               empty(array_diff($correctAnswers, $userAnswers));
        }
        
        $this->save();
        return $this->is_correct;
    }
}
