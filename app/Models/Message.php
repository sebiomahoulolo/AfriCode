<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'course_id',
        'content',
        'is_read',
        'read_at',
        'type', // 'text', 'file', 'image'
        'file_url',
        'file_name',
        'file_size'
    ];
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'file_size' => 'integer'
    ];

    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }
    public function course() { return $this->belongsTo(Course::class); }
}