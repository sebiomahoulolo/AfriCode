<?php

namespace App\Listeners;

use App\Events\CoursePublished;
use App\Jobs\SendNewCourseEmails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNewCourseEmailCampaign implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Déclenché lorsque l'admin publie un cours (validation formateur ou création/édition admin).
     * Envoie "Nouveau cours disponible" aux : apprenants, abonnés newsletter, et candidats (test complété).
     */
    public function handle(CoursePublished $event): void
    {
        $course = $event->course;

        if ($course->status !== 'published') {
            Log::warning("Campagne nouveau cours ignorée : cours non publié", [
                'course_id' => $course->id,
                'status' => $course->status,
            ]);
            return;
        }

        $campaignKey = "course_campaign_sent_{$course->id}";

        if (\Illuminate\Support\Facades\Cache::has($campaignKey)) {
            Log::info("Campagne déjà envoyée pour ce cours", ['course_id' => $course->id]);
            return;
        }

        SendNewCourseEmails::dispatch($course);
        \Illuminate\Support\Facades\Cache::put($campaignKey, true, now()->addHours(24));

        Log::info("Campagne email « Nouveau cours » déclenchée (apprenants, newsletter, candidats)", [
            'course_id' => $course->id,
            'course_title' => $course->title,
        ]);
    }
}

