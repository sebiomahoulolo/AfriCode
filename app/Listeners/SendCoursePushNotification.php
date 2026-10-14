<?php

namespace App\Listeners;

use App\Events\CoursePublished;
use App\Jobs\SendPushNotificationJob;
use App\Services\PushNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCoursePushNotification implements ShouldQueue
{
    public string $queue = 'notifications';

    public function handle(CoursePublished $event): void
    {
        $course = $event->course;

        if ($course->status !== 'published') {
            return;
        }

        $level = match ($course->level) {
            'debutant'      => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance'        => 'Avancé',
            default         => 'Tous niveaux',
        };

        $price = $course->price > 0
            ? number_format($course->price, 0, ',', ' ') . ' FCFA'
            : 'Gratuit';

        $payload = PushNotificationService::payload(
            title:   'Nouveau cours disponible !',
            body:    "{$course->title} · {$level} · {$price}",
            url:     "/courses/{$course->slug}",
            icon:    $course->cover_image_path
                        ? asset($course->cover_image_path)
                        : '/images/icons/africode-192.png',
            tag:     'nouveau-cours-' . $course->id,
            actions: [
                ['action' => 'voir',  'title' => 'Voir le cours'],
                ['action' => 'later', 'title' => 'Plus tard'],
            ]
        );

        // Récupère uniquement les abonnés qui ont activé "push_new_courses"
        $svc     = new PushNotificationService();
        $userIds = $svc->getUserIdsWithPreference('push_new_courses');

        if (!empty($userIds)) {
            SendPushNotificationJob::dispatch($payload, $userIds)->onQueue('notifications');
        }
    }
}
