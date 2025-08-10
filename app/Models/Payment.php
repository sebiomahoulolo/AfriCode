<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_id',
        'payment_gateway_id',
        'amount',
        'currency',
        'converted_amount',
        'converted_currency',
        'fees',
        'total_amount',
        'payment_method',
        'payment_gateway',
        'status',
        'transaction_id',
        'gateway_transaction_id',
        'external_transaction_id',
        'payment_date',
        'paid_at',
        'refund_status',
        'refund_date',
        'refund_amount',
        'payment_details',
        'gateway_response',
        'metadata',
        'failure_reason',
        'webhook_received_at',
        'payable_id',
        'payable_type'
    ];
    
    protected $casts = [
        'payment_date' => 'datetime',
        'paid_at' => 'datetime',
        'refund_date' => 'datetime',
        'webhook_received_at' => 'datetime',
        'payment_details' => 'array',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'amount' => 'decimal:2',
        'converted_amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2'
    ];

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    // Relations
    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function course() { 
        return $this->belongsTo(Course::class); 
    }
    
    public function enrollment() { 
        return $this->belongsTo(Enrollment::class); 
    }
    
    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeForCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }

    public function scopeForGateway($query, $gateway)
    {
        return $query->where('payment_gateway', $gateway);
    }

    // Méthodes utilitaires
    public function isSuccessful()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed()
    {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    public function isRefunded()
    {
        return in_array($this->status, [self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED]);
    }

    public function canBeRefunded()
    {
        return $this->status === self::STATUS_COMPLETED && !$this->isRefunded();
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_PENDING, self::STATUS_PROCESSING => 'badge-warning',
            self::STATUS_FAILED, self::STATUS_CANCELLED => 'badge-danger',
            self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED => 'badge-info',
            default => 'badge-secondary'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_PROCESSING => 'En cours',
            self::STATUS_COMPLETED => 'Complété',
            self::STATUS_FAILED => 'Échoué',
            self::STATUS_CANCELLED => 'Annulé',
            self::STATUS_REFUNDED => 'Remboursé',
            self::STATUS_PARTIALLY_REFUNDED => 'Partiellement remboursé',
            default => 'Inconnu'
        };
    }

    public function markAsCompleted($transactionId = null, $metadata = [])
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'paid_at' => now(),
            'external_transaction_id' => $transactionId,
            'metadata' => array_merge($this->metadata ?? [], $metadata)
        ]);
    }

    public function markAsFailed($reason = null, $metadata = [])
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failure_reason' => $reason,
            'metadata' => array_merge($this->metadata ?? [], $metadata)
        ]);
    }
}