<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'specialization_area',
        'status',
        'requested_at',
        'approved_at',
        'ended_at'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
    
    public function isActive()
    {
        return $this->status === 'active';
    }
    
    public function isPending()
    {
        return $this->status === 'pending_mentor_approval';
    }
    
    public function isDeclined()
    {
        return $this->status === 'declined';
    }
    
    public function isEnded()
    {
        return in_array($this->status, ['ended_by_mentor', 'ended_by_mentee']);
    }
    
    public function approve()
    {
        if ($this->isPending()) {
            $this->status = 'active';
            $this->approved_at = now();
            $this->save();
            return true;
        }
        return false;
    }
    
    public function decline()
    {
        if ($this->isPending()) {
            $this->status = 'declined';
            $this->save();
            return true;
        }
        return false;
    }
    
    public function endByMentor()
    {
        if ($this->isActive()) {
            $this->status = 'ended_by_mentor';
            $this->ended_at = now();
            $this->save();
            return true;
        }
        return false;
    }
    
    public function endByMentee()
    {
        if ($this->isActive()) {
            $this->status = 'ended_by_mentee';
            $this->ended_at = now();
            $this->save();
            return true;
        }
        return false;
    }
}
