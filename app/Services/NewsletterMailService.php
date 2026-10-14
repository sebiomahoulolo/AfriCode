<?php

namespace App\Services;

use App\Mail\GenericMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterMailService
{
    public function sendWelcomeEmail(NewsletterSubscriber $subscriber): bool
    {
        try {
            $data = [
                'email'          => $subscriber->email,
                'unsubscribeUrl' => route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]),
                'coursesUrl'     => route('courses.index'),
            ];

            Mail::to($subscriber->email)->queue(new GenericMail(
                'emails.newsletter.welcome',
                'emails.newsletter.welcome-text',
                $data,
                'Bienvenue à la newsletter AfriCode !'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email newsletter: ' . $e->getMessage());
            return false;
        }
    }
}
