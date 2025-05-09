<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'course_id', 'amount', 'currency', 'transaction_id',
        'status', 'payment_method', 'metadata',
    ];
    protected $casts = ['amount' => 'decimal:2', 'metadata' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
}