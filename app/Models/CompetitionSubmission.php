<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'project_title',
        'project_description',
        'project_link_repository',
        'project_link_live',
        'submitted_at',
        'score',
        'rank',
        'feedback_from_judges'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function registration()
    {
        return $this->belongsTo(CompetitionRegistration::class);
    }
    
    public function user()
    {
        return $this->registration->user();
    }
    
    public function competition()
    {
        return $this->registration->competition();
    }
}
