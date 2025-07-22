<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionTestCase extends Model
{
    protected $fillable = [
        'competition_id',
        'input',
        'expected_output',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
} 