<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 
        'slug', 
        'short_description',
        'full_description',
        'learning_objectives',
        'prerequisites',
        'faq',
        'testimonials',
        'level',
        'price',
        'currency',
        'cover_image_path',
        'status',
        'formateur_id',
        'category_id',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
        'learning_objectives' => 'array',
        'prerequisites' => 'array',
        'faq' => 'array',
        'testimonials' => 'array'
    ];

    // --- Relations ---
    public function formateur() {
        return $this->belongsTo(User::class, 'formateur_id');
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function modules() {
        return $this->hasMany(Module::class);
    }
    
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    
    public function students() {
        return $this->belongsToMany(User::class, 'enrollments')
                ->withPivot('enrolled_at', 'completed_at', 'progress_percentage')
                ->withTimestamps();
    }
    
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    
    public function ratings() {
        return $this->hasMany(Rating::class);
    }
    
    public function certifications() {
        return $this->hasMany(Certification::class);
    }
    
    public function forums() {
        return $this->hasMany(CourseForum::class);
    }
    
    public function quizzes() {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    // Générer le slug automatiquement lors de la création/mise à jour
    protected static function boot() {
        parent::boot();
        static::saving(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }
    
    public function isPublished() {
        return $this->status === 'published' && $this->published_at !== null && $this->published_at <= now();
    }
    
    public function getLessonsCount() {
        return $this->hasManyThrough(Lesson::class, Module::class)->count();
    }
    
    public function getEstimatedDuration() {
        return $this->hasManyThrough(Lesson::class, Module::class)->sum('duration_minutes');
    }
}