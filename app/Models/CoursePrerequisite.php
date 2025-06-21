<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePrerequisite extends Model
{
    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'prerequisite_course_id',
        'type',
        'minimum_score',
        'description',
    ];

    /**
     * Le cours qui a ce prérequis.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Le cours qui est requis comme prérequis.
     */
    public function prerequisiteCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'prerequisite_course_id');
    }

    /**
     * Vérifie si un utilisateur a satisfait ce prérequis.
     */
    public function isSatisfiedBy(User $user): bool
    {
        $enrollment = $user->enrollments()
            ->where('course_id', $this->prerequisite_course_id)
            ->where('status', 'completed')
            ->first();

        if (!$enrollment) {
            return false;
        }

        if ($this->minimum_score && $enrollment->final_score < $this->minimum_score) {
            return false;
        }

        return true;
    }

    /**
     * Vérifie si un utilisateur peut accéder au cours en fonction des prérequis.
     */
    public static function canAccessCourse(User $user, Course $course): bool
    {
        $prerequisites = $course->prerequisites()->where('type', 'required')->get();

        if ($prerequisites->isEmpty()) {
            return true;
        }

        return $prerequisites->every(function ($prerequisite) use ($user) {
            return $prerequisite->isSatisfiedBy($user);
        });
    }

    /**
     * Récupère les prérequis manquants pour un utilisateur.
     */
    public static function getMissingPrerequisites(User $user, Course $course): array
    {
        $prerequisites = $course->prerequisites()->where('type', 'required')->get();
        $missing = [];

        foreach ($prerequisites as $prerequisite) {
            if (!$prerequisite->isSatisfiedBy($user)) {
                $missing[] = [
                    'course' => $prerequisite->prerequisiteCourse,
                    'minimum_score' => $prerequisite->minimum_score,
                    'description' => $prerequisite->description,
                ];
            }
        }

        return $missing;
    }
} 