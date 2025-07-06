<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'payable_id',
        'payable_type',
        'amount',
        'currency',
        'payment_method',
        'status',
        'transaction_id',
        'payment_date',
        'refund_status',
        'refund_date',
        'payment_details'
    ];
    protected $casts = [
        'payment_date' => 'datetime',
        'refund_date' => 'datetime',
        'payment_details' => 'array'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function payable() { return $this->morphTo(); }
}