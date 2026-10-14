<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'competition_id',
        'team_name',
        'registered_at'
    ];

    protected $casts = [
        'registered_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function submission()
    {
        return $this->hasOne(CompetitionSubmission::class, 'registration_id');
    }

    public function hasSubmission()
    {
        return $this->submission()->exists();
    }
}
