<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ProgressionMilestone;
use App\Models\Payment;
use App\Jobs\SendPushNotificationJob;
use App\Services\PushNotificationService;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'completed_at',
        'progress_percentage'
    ];

    protected static function boot()
    {
        parent::boot();

        // Validation pour s'assurer qu'une inscription ne peut pas être activée sans paiement valide
        static::updating(function ($enrollment) {
            if ($enrollment->isDirty('enrolled_at') && $enrollment->enrolled_at !== null) {
                $course = $enrollment->course;
                
                // Pour les cours payants, vérifier qu'il y a un paiement valide
                if ($course && $course->price > 0) {
                    $validPayment = Payment::where('enrollment_id', $enrollment->id)
                        ->where('status', 'succeeded')
                        ->exists();
                        
                    if (!$validPayment) {
                        throw new \Exception('Impossible d\'activer l\'inscription sans paiement valide pour un cours payant.');
                    }
                }
            }
        });
    }

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function lessonCompletions()
    {
        return $this->hasMany(LessonCompletion::class);
    }
    
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
    
    public function certification()
    {
        return $this->hasOne(Certification::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function isCompleted()
    {
        return $this->completed_at !== null;
    }
    
    public function calculateProgressPercentage()
    {
        $course = $this->course;
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($course->modules as $module) {
            $moduleLessons = $module->lessons()->count();
            $totalLessons += $moduleLessons;
            $completedLessons += $this->lessonCompletions()
                ->whereIn('lesson_id', $module->lessons()->pluck('id'))
                ->count();
        }
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        return round(($completedLessons / $totalLessons) * 100);
    }
    
    public function updateProgress()
    {
        $course = $this->course;
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($course->modules as $module) {
            $moduleLessons = $module->lessons()->count();
            $totalLessons += $moduleLessons;
            $completedLessons += $this->lessonCompletions()
                ->whereIn('lesson_id', $module->lessons()->pluck('id'))
                ->count();
        }
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        $progress = round(($completedLessons / $totalLessons) * 100);
        $this->progress_percentage = $progress;
        $this->save();

        // Notifier l'utilisateur s'il atteint 50% de progression
        if ($progress === 50) {
            $this->user->notify(new ProgressionMilestone());

            // Push notification — mi-parcours
            SendPushNotificationJob::dispatch(
                PushNotificationService::payload(
                    title: '🏆 Mi-parcours atteint !',
                    body:  "Bravo ! Vous avez complété 50% de \"{$this->course->title}\". Continuez !",
                    url:   "/courses/{$this->course->slug}",
                    tag:   'progress-50-' . $this->id,
                ),
                [$this->user_id]
            )->onQueue('notifications');
        }

        // Push notification — cours complété à 100%
        if ($progress === 100 && !$this->completed_at) {
            SendPushNotificationJob::dispatch(
                PushNotificationService::payload(
                    title: '🎉 Cours terminé !',
                    body:  "Félicitations ! Vous avez terminé \"{$this->course->title}\"." .
                           ($this->course->is_certifying ? ' Votre certificat est disponible !' : ''),
                    url:   "/courses/{$this->course->slug}",
                    tag:   'completed-' . $this->id,
                ),
                [$this->user_id]
            )->onQueue('notifications');
        }

        return $progress;
    }
}
