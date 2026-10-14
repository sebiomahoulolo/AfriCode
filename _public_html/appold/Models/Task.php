<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'due_date', 'priority', 'is_completed', 'assigned_to_id'
    ];
    protected $casts = ['due_date' => 'date', 'is_completed' => 'boolean'];

    public function assignee() { return $this->belongsTo(User::class, 'assigned_to_id'); }
}