<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'points',
        'required'
    ];

    protected $casts = [
        'points' => 'integer',
        'required' => 'boolean'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function validateAnswer($answer): bool
    {
        switch ($this->type) {
            case 'multiple_choice':
                return $this->validateMultipleChoice($answer);
            case 'true_false':
                return $this->validateTrueFalse($answer);
            case 'short_answer':
                return $this->validateShortAnswer($answer);
            case 'essay':
                return true; // Les essais doivent être évalués manuellement
            default:
                return false;
        }
    }

    protected function validateMultipleChoice($answer): bool
    {
        $correctOptions = $this->options()->where('is_correct', true)->pluck('id')->toArray();
        return empty(array_diff($correctOptions, (array)$answer));
    }

    protected function validateTrueFalse($answer): bool
    {
        $correctOption = $this->options()->where('is_correct', true)->first();
        return $correctOption && $answer == $correctOption->id;
    }

    protected function validateShortAnswer($answer): bool
    {
        $correctOptions = $this->options()->where('is_correct', true)
            ->pluck('option')
            ->map(function ($option) {
                return strtolower(trim($option));
            })
            ->toArray();

        return in_array(strtolower(trim($answer)), $correctOptions);
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
