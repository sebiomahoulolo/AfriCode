<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormateurDisciplinaryAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'formateur_id',
        'admin_id',
        'action_type',
        'reason',
        'details',
        'reference_type',
        'reference_id',
        'action_date',
        'expires_at',
        'is_active',
        'is_resolved',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'action_date' => 'datetime',
        'expires_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_active' => 'boolean',
        'is_resolved' => 'boolean',
    ];

    // Relations
    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function reference()
    {
        return $this->morphTo('reference');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('action_type', $type);
    }

    // Méthodes
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }
        return $this->expires_at->isPast();
    }

    public function resolve($notes = null)
    {
        $this->update([
            'is_resolved' => true,
            'is_active' => false,
            'resolution_notes' => $notes,
            'resolved_at' => now(),
        ]);
    }

    public function getActionLabelAttribute()
    {
        return match($this->action_type) {
            'warning' => 'Avertissement',
            'suspension_7d' => 'Suspension 7 jours',
            'suspension_30d' => 'Suspension 30 jours',
            'ban' => 'Bannissement définitif',
            default => 'Action inconnue',
        };
    }
}

