<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'enrollment_id', 'payable_id', 'payable_type', 
        'amount', 'currency', 'transaction_id', 'status', 'payment_gateway',
        'paid_at'
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function enrollment() { return $this->belongsTo(Enrollment::class); }
    public function payable() { return $this->morphTo(); }
    
    // Pour maintenir la compatibilité avec le code existant
    public function course() { 
        if ($this->payable_type === 'App\Models\Course') {
            return $this->belongsTo(Course::class, 'payable_id');
        }
        return null;
    }
}