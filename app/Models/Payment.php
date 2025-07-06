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
        'amount',
        'currency',
        'payment_method',
        'payment_gateway',
        'status',
        'transaction_id',
        'payment_date',
        'paid_at',
        'refund_status',
        'refund_date',
        'payment_details'
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
}