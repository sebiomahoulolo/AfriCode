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
        'published_at',
        'is_certifying',
        'is_premium'
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
    
    // Relation directe pour les quiz finaux du cours
    public function finalQuiz()
    {
        return $this->hasOne(Quiz::class)->where('quiz_type', 'course_final');
    }
    
    public function quizzes() {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Les prérequis de ce cours.
     */
    public function prerequisites()
    {
        return $this->hasMany(CoursePrerequisite::class);
    }

    /**
     * Les cours qui ont ce cours comme prérequis.
     */
    public function requiredFor()
    {
        return $this->hasMany(CoursePrerequisite::class, 'prerequisite_course_id');
    }

    /**
     * Vérifie si un utilisateur peut accéder à ce cours.
     */
    public function canBeAccessedBy(User $user): bool
    {
        return CoursePrerequisite::canAccessCourse($user, $this);
    }

    /**
     * Récupère les prérequis manquants pour un utilisateur.
     */
    public function getMissingPrerequisitesFor(User $user): array
    {
        return CoursePrerequisite::getMissingPrerequisites($user, $this);
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
    
    // Vérifier si tous les modules sont complétés (leçons + quiz)
    public function allModulesCompletedByUser($userId)
    {
        $modules = $this->modules;
        foreach ($modules as $module) {
            if (!$module->isCompletedByUser($userId)) {
                return false;
            }
        }
        return true;
    }
    
    // Vérifier si le quiz final est réussi
    public function finalQuizPassedByUser($userId)
    {
        $finalQuiz = $this->finalQuiz;
        if (!$finalQuiz) return true; // Pas de quiz final = considéré comme réussi
        
        return $finalQuiz->isPassedByUser($userId);
    }
    
    // Vérifier si le cours est complètement terminé
    public function isCompletedByUser($userId)
    {
        return $this->allModulesCompletedByUser($userId) && $this->finalQuizPassedByUser($userId);
    }
    
    // Vérifier si l'utilisateur peut accéder au quiz final
    public function canUserAccessFinalQuiz($userId)
    {
        return $this->allModulesCompletedByUser($userId);
    }
}