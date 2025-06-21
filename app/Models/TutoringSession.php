<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutoringSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tutor_id',
        'student_id',
        'title',
        'description',
        'status',
        'type',
        'start_time',
        'end_time',
        'duration_minutes',
        'price',
        'meeting_link',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
        'price' => 'decimal:2'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function participants()
    {
        return $this->hasMany(TutoringSessionParticipant::class, 'session_id');
    }

    public function resources()
    {
        return $this->hasMany(TutoringResource::class, 'session_id');
    }

    public function feedback()
    {
        return $this->hasMany(TutoringFeedback::class, 'session_id');
    }

    public function learningObjectives()
    {
        return $this->hasMany(TutoringLearningObjective::class, 'session_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())
            ->where('status', 'scheduled');
    }

    public function scopePast($query)
    {
        return $query->where('end_time', '<', now())
            ->where('status', 'completed');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isUpcoming()
    {
        return $this->start_time > now() && $this->status === 'scheduled';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function start()
    {
        if ($this->isUpcoming()) {
            $this->update(['status' => 'in_progress']);
            return true;
        }
        return false;
    }

    public function complete()
    {
        if ($this->isInProgress()) {
            $this->update(['status' => 'completed']);
            return true;
        }
        return false;
    }

    public function cancel()
    {
        if (!$this->isCompleted() && !$this->isCancelled()) {
            $this->update(['status' => 'cancelled']);
            return true;
        }
        return false;
    }

    public function addParticipant($userId, $role = 'student')
    {
        return $this->participants()->create([
            'user_id' => $userId,
            'role' => $role
        ]);
    }

    public function addResource($data)
    {
        return $this->resources()->create($data);
    }

    public function addLearningObjective($objective)
    {
        return $this->learningObjectives()->create([
            'objective' => $objective
        ]);
    }

    public function markObjectiveAsAchieved($objectiveId)
    {
        $objective = $this->learningObjectives()->find($objectiveId);
        if ($objective) {
            $objective->update(['is_achieved' => true]);
            return true;
        }
        return false;
    }
}
