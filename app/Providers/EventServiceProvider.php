<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmailAfterVerification;
use App\Events\CourseCreated;
use App\Events\CoursePublished;
use App\Events\PaymentSucceeded;
use App\Listeners\NotifyFormateurStudentPaid;
use App\Listeners\SendCourseCreatedNotification;
use App\Listeners\NotifyFormateurCoursePublished;
use App\Listeners\SendNewCourseEmailCampaign;
use App\Listeners\SendCoursePushNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Verified::class => [
            SendWelcomeEmailAfterVerification::class,
        ],
        CourseCreated::class => [
            SendCourseCreatedNotification::class,
        ],
        CoursePublished::class => [
            SendNewCourseEmailCampaign::class,
            NotifyFormateurCoursePublished::class,
            SendCoursePushNotification::class,   // Push notification à tous les abonnés
        ],
        PaymentSucceeded::class => [
            NotifyFormateurStudentPaid::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
