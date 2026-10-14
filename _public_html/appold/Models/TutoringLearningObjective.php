<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringLearningObjective extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'objective',
        'is_achieved',
        'notes'
    ];

    protected $casts = [
        'is_achieved' => 'boolean'
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }

    public function scopeAchieved($query)
    {
        return $query->where('is_achieved', true);
    }

    public function scopeNotAchieved($query)
    {
        return $query->where('is_achieved', false);
    }

    public function markAsAchieved($notes = null)
    {
        $this->update([
            'is_achieved' => true,
            'notes' => $notes
        ]);
    }

    public function markAsNotAchieved($notes = null)
    {
        $this->update([
            'is_achieved' => false,
            'notes' => $notes
        ]);
    }
}
