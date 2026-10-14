<?php

namespace App\Listeners;

use App\Events\CoursePublished;
use App\Services\CourseMailService;
use Illuminate\Support\Facades\Log;

class NotifyFormateurCoursePublished
{
    /**
     * Notifie le formateur par email que son cours a été validé et publié.
     */
    public function handle(CoursePublished $event): void
    {
        $course = $event->course;

        if ($course->status !== 'published' || !$course->formateur_id) {
            return;
        }

        $course->load('formateur');
        if (!$course->formateur) {
            return;
        }

        try {
            $courseMailService = app(CourseMailService::class);
            if ($courseMailService->sendCourseValidatedToFormateur($course)) {
                Log::info('Email « cours validé » envoyé au formateur', [
                    'course_id' => $course->id,
                    'formateur_id' => $course->formateur_id,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Erreur notification formateur (cours validé): ' . $e->getMessage(), [
                'course_id' => $course->id,
            ]);
        }
    }
}
