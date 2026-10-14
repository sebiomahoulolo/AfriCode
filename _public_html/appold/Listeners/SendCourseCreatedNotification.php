<?php

namespace App\Listeners;

use App\Events\CourseCreated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCourseCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CourseCreated $event): void
    {
        $course = $event->course;

        // Créer une notification pour l'administrateur
        NotificationService::userAction('created', 'course', $course->title, [
            'model_id' => $course->id,
            'action_url' => route('admin.courses.show', $course->id),
            'action_text' => 'Voir le cours',
            'icon' => 'graduation-cap',
            'color' => 'success'
        ]);

        // Invalider le cache des cours populaires
        \Illuminate\Support\Facades\Cache::forget('popular_courses');
        \Illuminate\Support\Facades\Cache::forget('all_categories');
    }
}
