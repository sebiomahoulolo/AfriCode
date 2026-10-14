<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeQuestion extends Model
{
    protected $fillable = [
        'challenge_id',
        'question_text',
    ];

    public function options()
    {
        return $this->hasMany(ChallengeQuestionOption::class, 'question_id');
    }
} 