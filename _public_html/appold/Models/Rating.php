<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'rating',
        'comment'
    ];

    // Relation avec l'utilisateur qui a laissé l'évaluation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le cours évalué
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
