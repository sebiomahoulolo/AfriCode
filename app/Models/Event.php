<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'start_datetime', 'end_datetime', 'color'];
    protected $casts = ['start_datetime' => 'datetime', 'end_datetime' => 'datetime'];
}