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
        'phone',
        'date_of_birth',
        'city',
        'country',
        'is_active',
        'password_reset_token',
        'password_reset_expires_at'
    ];

    protected $hidden = ['password', 'remember_token', 'password_reset_token'];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
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

    /**
     * Les badges obtenus par l'utilisateur.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Les récompenses obtenues par l'utilisateur.
     */
    public function rewards()
    {
        return $this->belongsToMany(Reward::class)
            ->withPivot('claimed_at', 'status')
            ->withTimestamps();
    }

    /**
     * La progression de l'utilisateur.
     */
    public function progress()
    {
        return $this->hasOne(UserProgress::class);
    }

    /**
     * Les activités de l'utilisateur.
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Vérifie si l'utilisateur a un badge spécifique.
     */
    public function hasBadge(string $badgeName): bool
    {
        return $this->badges()->where('name', $badgeName)->exists();
    }

    /**
     * Vérifie si l'utilisateur a une récompense spécifique.
     */
    public function hasReward(string $rewardName): bool
    {
        return $this->rewards()->where('name', $rewardName)->exists();
    }

    /**
     * Récupère les badges non obtenus par l'utilisateur.
     */
    public function getAvailableBadges()
    {
        return Badge::where('is_active', true)
            ->whereDoesntHave('users', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->get()
            ->filter(function ($badge) {
                return $badge->checkRequirements($this);
            });
    }

    /**
     * Récupère les récompenses disponibles pour l'utilisateur.
     */
    public function getAvailableRewards()
    {
        return Reward::where('is_active', true)
            ->whereDoesntHave('users', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->get()
            ->filter(function ($reward) {
                return $reward->checkRequirements($this);
            });
    }

    /**
     * Récupère les statistiques de l'utilisateur.
     */
    public function getStats(): array
    {
        return [
            'badges' => [
                'total' => $this->badges()->count(),
                'recent' => $this->badges()->latest('earned_at')->take(5)->get(),
            ],
            'rewards' => [
                'total' => $this->rewards()->count(),
                'claimed' => $this->rewards()->where('is_claimed', true)->count(),
                'available' => $this->getAvailableRewards()->count(),
            ],
            'progress' => [
                'level' => $this->progress->current_level ?? 1,
                'points' => $this->progress->total_points ?? 0,
                'experience' => $this->progress->experience_points ?? 0,
                'next_level' => $this->progress ? $this->progress->getLevelProgressPercentage() : 0,
            ],
            'activities' => UserActivity::getUserStats($this),
        ];
    }

    /**
     * Enregistre une nouvelle activité pour l'utilisateur.
     */
    public function logActivity(string $type, string $description, array $metadata = [], int $points = 0): UserActivity
    {
        return UserActivity::log($this, $type, $description, $metadata, $points);
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
    
    public function getNameAttribute() {
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

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class)
            ->withPivot('progress', 'is_completed', 'completed_at')
            ->withTimestamps();
    }

    public function getActiveChallenges()
    {
        return $this->challenges()
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->get();
    }

    public function getCompletedChallenges()
    {
        return $this->challenges()
            ->wherePivot('is_completed', true)
            ->get();
    }

    public function getEarnedBadges()
    {
        return $this->badges()
            ->wherePivotNotNull('earned_at')
            ->get();
    }

    public function getTotalPoints()
    {
        return $this->progress->total_points;
    }

    public function getCurrentLevel()
    {
        return $this->progress->current_level;
    }

    public function getLevelProgress()
    {
        return $this->progress->getLevelProgressPercentage();
    }

    public function getGlobalRanking()
    {
        return User::where('total_points', '>', $this->getTotalPoints())->count() + 1;
    }

    public function getRecentAchievements($limit = 5)
    {
        return $this->activities()
            ->whereIn('type', ['badge_earned', 'challenge_completed', 'reward_claimed'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}