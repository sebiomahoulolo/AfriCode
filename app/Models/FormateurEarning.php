<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormateurEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'formateur_id',
        'course_id',
        'enrollment_id',
        'course_price',
        'formateur_share',
        'africode_share',
        'bonus_amount',
        'student_count',
        'total_earnings',
        'payment_status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'course_price' => 'decimal:2',
        'formateur_share' => 'decimal:2',
        'africode_share' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'paid_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    // Relations
    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
}
