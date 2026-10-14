<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use App\Models\JobApplication;
use App\Services\CourseMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNewCourseEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $course;
    public $tries = 3;
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Execute the job : envoi "Nouveau cours disponible" aux apprenants et aux abonnés newsletter.
     * Déclenché quand un cours est publié par l'admin (validation ou création/édition).
     */
    public function handle(CourseMailService $courseMailService): void
    {
        try {
            $sentCount = 0;
            $failedCount = 0;

            // 1. Apprenants — seulement ceux qui ont activé email_new_courses (défaut = true)
            $users = User::where('role', 'apprenant')
                ->get()
                ->filter(function (User $u) {
                    $prefs = $u->notification_preferences ?? [];
                    return $prefs['email_new_courses'] ?? true;
                });

            $apprenantEmails = $users->pluck('email')->map(fn ($e) => strtolower($e))->flip()->all();

            foreach ($users as $user) {
                try {
                    $cacheKey = "course_email_sent_{$this->course->id}_{$user->id}";

                    if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                        if ($courseMailService->sendNewCourseNotification($this->course, $user)) {
                            \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addHours(24));
                            $sentCount++;
                        } else {
                            $failedCount++;
                        }
                        usleep(100000); // 0.1 s entre chaque envoi
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Erreur envoi email nouveau cours à {$user->email}: " . $e->getMessage(), [
                        'course_id' => $this->course->id,
                        'user_id' => $user->id,
                    ]);
                }
            }

            // 2. Abonnés newsletter (emails qui ne sont pas déjà apprenants, pour éviter doublon)
            $newsletterSubscribers = NewsletterSubscriber::all();
            foreach ($newsletterSubscribers as $subscriber) {
                $email = $subscriber->email;
                $emailLower = strtolower($email);
                if (isset($apprenantEmails[$emailLower])) {
                    continue; // déjà reçu en tant qu'apprenant
                }

                $cacheKey = "course_email_sent_{$this->course->id}_newsletter_{$subscriber->id}";
                if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                    continue;
                }

                try {
                    if ($courseMailService->sendNewCourseNotificationToEmail($this->course, $email, $subscriber)) {
                        \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addHours(24));
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                    usleep(100000);
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Erreur envoi email nouveau cours (newsletter) à {$email}: " . $e->getMessage(), [
                        'course_id' => $this->course->id,
                        'subscriber_id' => $subscriber->id,
                    ]);
                }
            }

            // 3. Candidats recrutement (test complété) : emails qui ne sont pas déjà apprenants ni newsletter
            $candidates = JobApplication::where('status', 'test_completed')
                ->select('email', 'nom_prenoms')
                ->get()
                ->unique('email');
            foreach ($candidates as $candidate) {
                $emailLower = strtolower($candidate->email);
                if (isset($apprenantEmails[$emailLower])) {
                    continue;
                }
                $alreadyNewsletter = $newsletterSubscribers->contains(fn ($s) => strtolower($s->email) === $emailLower);
                if ($alreadyNewsletter) {
                    continue;
                }

                $cacheKey = "course_email_sent_{$this->course->id}_candidate_{$candidate->email}";
                if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                    continue;
                }

                try {
                    if ($courseMailService->sendNewCourseNotificationToEmail($this->course, $candidate->email, null, $candidate->nom_prenoms)) {
                        \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addHours(24));
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                    usleep(100000);
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Erreur envoi email nouveau cours (candidat) à {$candidate->email}: " . $e->getMessage(), [
                        'course_id' => $this->course->id,
                    ]);
                }
            }

            $totalRecipients = $users->count() + $newsletterSubscribers->filter(function ($s) use ($apprenantEmails) {
                return !isset($apprenantEmails[strtolower($s->email)]);
            })->count() + $candidates->count();

            Log::info("Campagne email nouveau cours terminée", [
                'course_id' => $this->course->id,
                'course_title' => $this->course->title,
                'sent' => $sentCount,
                'failed' => $failedCount,
                'total_recipients' => $totalRecipients,
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur critique dans SendNewCourseEmails: " . $e->getMessage(), [
                'course_id' => $this->course->id,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job SendNewCourseEmails a échoué", [
            'course_id' => $this->course->id,
            'error' => $exception->getMessage(),
        ]);
    }
}

