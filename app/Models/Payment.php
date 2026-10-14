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
        'payable_id',
        'payable_type',
        'amount',
        'currency',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'payment_date',
        'payment_details',
        'status',
        'paid_at',
    ];
    protected $casts = [
        'payment_date' => 'datetime',
        'paid_at' => 'datetime',
        'refund_date' => 'datetime',
        'payment_details' => 'array'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function enrollment() { return $this->belongsTo(Enrollment::class); }
    public function payable() { return $this->morphTo(); }

    public function markAsSucceeded(string $transactionId, array $extraDetails = []): void
    {
        $this->status = 'succeeded';
        $this->paid_at = now();
        $this->transaction_id = $transactionId;
        $this->payment_details = array_merge($this->payment_details ?? [], $extraDetails);
        $this->save();
    }

    public function markAsFailed(string $reason = ''): void
    {
        $this->status = 'failed';
        $this->payment_details = array_merge($this->payment_details ?? [], ['failure_reason' => $reason]);
        $this->save();
    }
}