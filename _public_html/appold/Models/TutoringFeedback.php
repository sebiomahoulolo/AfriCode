<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'rating',
        'comment',
        'specific_ratings'
    ];

    protected $casts = [
        'rating' => 'integer',
        'specific_ratings' => 'array'
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment');
    }

    public function scopeBySpecificRating($query, $category, $value)
    {
        return $query->whereJsonContains('specific_ratings->' . $category, $value);
    }

    public function getAverageSpecificRating()
    {
        if (!$this->specific_ratings) {
            return null;
        }

        return collect($this->specific_ratings)->avg();
    }
}
