<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Services\CourseMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NotifyFormateurStudentPaid implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries   = 3;
    public int $backoff = 120; // 2 min entre chaque retry

    public function handle(PaymentSucceeded $event): void
    {
        $payment = $event->payment;

        if ($payment->status !== 'succeeded') {
            return;
        }

        $cacheKey = "formateur_student_paid_sent_{$payment->id}";
        if (Cache::has($cacheKey)) {
            return;
        }

        $enrollment = $payment->enrollment;
        if (!$enrollment || !$enrollment->enrolled_at) {
            return;
        }

        $enrollment->load(['course.formateur', 'user']);
        if (!$enrollment->course || !$enrollment->course->formateur_id) {
            return;
        }

        $courseMailService = app(CourseMailService::class);
        $sent = $courseMailService->sendStudentPaidToFormateur($enrollment, $payment);

        if ($sent) {
            Cache::put($cacheKey, true, now()->addDays(1));
            Log::info('Email « étudiant a payé » envoyé au formateur', [
                'enrollment_id' => $enrollment->id,
                'course_id'     => $enrollment->course_id,
                'payment_id'    => $payment->id,
            ]);
        } else {
            Log::warning('Envoi email formateur échoué, tentative ' . $this->attempts() . '/' . $this->tries, [
                'enrollment_id' => $enrollment->id,
                'payment_id'    => $payment->id,
            ]);
            throw new \RuntimeException('Échec envoi email formateur pour payment #' . $payment->id);
        }
    }

    public function failed(PaymentSucceeded $event, \Throwable $exception): void
    {
        Log::error('Listener NotifyFormateurStudentPaid échoué définitivement', [
            'payment_id' => $event->payment->id,
            'error'      => $exception->getMessage(),
        ]);
    }
}
