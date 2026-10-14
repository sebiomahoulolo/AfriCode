<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormateurWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'formateur_id',
        'amount',
        'currency',
        'payment_method',
        'payment_account',
        'payment_reference',
        'status',
        'processed_by',
        'processed_at',
        'processing_notes',
        'failure_reason',
        'earnings_ids',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'earnings_ids' => 'array',
    ];

    // Relations
    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByFormateur($query, $formateurId)
    {
        return $query->where('formateur_id', $formateurId);
    }

    // Méthodes
    public function process($adminId, $notes = null)
    {
        $this->update([
            'status' => 'processing',
            'processed_by' => $adminId,
            'processed_at' => now(),
            'processing_notes' => $notes,
        ]);
    }

    public function complete($reference = null)
    {
        $this->update([
            'status' => 'completed',
            'payment_reference' => $reference,
        ]);
    }

    public function fail($reason)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'completed' => 'Complété',
            'failed' => 'Échoué',
            'cancelled' => 'Annulé',
            default => 'Inconnu',
        };
    }
}

