<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Pour le slug

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'category', 'price',
        'duration', 'instructor_id', 'image', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean', 'price' => 'decimal:2'];

    // --- Relations ---
    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    public function certifications() {
        return $this->hasMany(Certification::class);
    }
    // Ajoutez la relation ManyToMany pour les étudiants inscrits si nécessaire

    // Générer le slug automatiquement lors de la création/mise à jour
    protected static function boot() {
        parent::boot();
        static::saving(function ($course) {
            $course->slug = Str::slug($course->title);
        });
    }
}