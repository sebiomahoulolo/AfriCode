<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushCampaign extends Model
{
    protected $fillable = [
        'title', 'body', 'url', 'icon', 'tag',
        'target_type', 'target_value',
        'scheduled_at', 'sent_at', 'status',
        'recipients_count', 'created_by',
    ];

    protected $casts = [
        'target_value'  => 'array',
        'scheduled_at'  => 'datetime',
        'sent_at'       => 'datetime',
        'recipients_count' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft'     => 'Brouillon',
            'scheduled' => 'Planifiée',
            'sending'   => 'En cours',
            'sent'      => 'Envoyée',
            'failed'    => 'Échec',
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'draft'     => 'secondary',
            'scheduled' => 'warning',
            'sending'   => 'info',
            'sent'      => 'success',
            'failed'    => 'danger',
            default     => 'secondary',
        };
    }

    public function targetLabel(): string
    {
        return match ($this->target_type) {
            'all'             => 'Tous les abonnés',
            'role'            => 'Rôle : ' . ($this->target_value['role'] ?? '?'),
            'course_enrolled' => 'Inscrits au cours #' . ($this->target_value['course_id'] ?? '?'),
            'inactive_days'   => 'Inactifs depuis ' . ($this->target_value['days'] ?? '?') . ' jours',
            default           => $this->target_type,
        };
    }

    public function scopeSent($q)    { return $q->where('status', 'sent'); }
    public function scopeDraft($q)   { return $q->where('status', 'draft'); }
    public function scopePending($q) { return $q->whereIn('status', ['draft', 'scheduled']); }
}
