<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'bio',
        'profile_image_path',
        'is_active',
        'password_reset_token',
        'password_reset_expires_at'
    ];

    protected $hidden = ['password', 'remember_token', 'password_reset_token'];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'password_reset_expires_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // --- Relations ---
    public function coursesInstructed() {
        return $this->hasMany(Course::class, 'formateur_id');
    }
    
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    
    public function enrolledCourses() {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_at', 'completed_at', 'progress_percentage')
            ->withTimestamps();
    }
    
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    
    public function certifications() {
        return $this->hasMany(Certification::class);
    }
    
    public function lessonCompletions() {
        return $this->hasMany(LessonCompletion::class);
    }
    
    public function quizAttempts() {
        return $this->hasMany(QuizAttempt::class);
    }
    
    public function sentMessages() {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    public function receivedMessages() {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    
    public function tasksAssigned() {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }
    
    public function courseForums() {
        return $this->hasMany(CourseForum::class);
    }
    
    public function forumPosts() {
        return $this->hasMany(ForumPost::class);
    }
    
    public function mentorships() {
        return $this->hasMany(Mentorship::class, 'mentor_id');
    }
    
    public function mentoringReceived() {
        return $this->hasMany(Mentorship::class, 'mentee_id');
    }
    
    public function competitionRegistrations() {
        return $this->hasMany(CompetitionRegistration::class);
    }
    
    public function createdAnnouncements() {
        return $this->hasMany(Announcement::class);
    }
    
    public function notifications() {
        return $this->hasMany(Notification::class);
    }

    // --- Role Checks ---
    public function isAdmin()
    {
        return $this->role === 'administrateur';
    }

    public function isFormateur()
    {
        return $this->role === 'formateur';
    }

    public function isApprenant()
    {
        return $this->role === 'apprenant';
    }
    
    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    /**
     * Check if user is enrolled in a specific course
     * 
     * @param int $courseId
     * @return bool
     */
    public function isEnrolledIn($courseId)
    {
        return $this->enrollments()->where('course_id', $courseId)->exists();
    }
    
    /**
     * Get course enrollment
     * 
     * @param int $courseId
     * @return Enrollment|null
     */
    public function getEnrollment($courseId)
    {
        return $this->enrollments()->where('course_id', $courseId)->first();
    }
}