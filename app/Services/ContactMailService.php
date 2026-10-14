<?php

namespace App\Services;

use App\Mail\GenericMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactMailService
{
    public function sendContactEmail(array $contactData): bool
    {
        try {
            $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'africodesm@gmail.com'));
            $senderName = $contactData['firstName'] . ' ' . $contactData['lastName'];

            $attachmentData = $contactData['attachmentData'] ?? null;
            $attachmentName = $contactData['attachmentName'] ?? null;
            $attachmentMime = $contactData['attachmentMime'] ?? null;

            // Données de vue sans le binaire (inutile dans le template)
            $viewData = array_diff_key($contactData, array_flip(['attachmentData']));

            // Email à l'administrateur (avec reply-to vers l'expéditeur)
            Mail::to($adminEmail)->queue(new GenericMail(
                'emails.contact.admin',
                'emails.contact.admin-text',
                ['contactData' => $viewData],
                'Nouveau message de contact - ' . $contactData['subject'],
                $contactData['email'],
                $senderName,
                $attachmentData,
                $attachmentName,
                $attachmentMime
            ));

            // Email de confirmation à l'expéditeur
            Mail::to($contactData['email'])->queue(new GenericMail(
                'emails.contact.confirmation',
                'emails.contact.confirmation-text',
                ['contactData' => $viewData, 'coursesUrl' => route('courses.index')],
                'Confirmation de votre message - AfriCode'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email contact: ' . $e->getMessage());
            return false;
        }
    }

    public function subscribeToNewsletter(string $email): array
    {
        $existing = NewsletterSubscriber::where('email', $email)->first();
        if ($existing) {
            return ['success' => false, 'message' => 'Cet email est déjà inscrit à la newsletter.'];
        }

        try {
            $subscriber = NewsletterSubscriber::create([
                'email'             => $email,
                'unsubscribe_token' => Str::random(32),
            ]);

            Mail::to($email)->queue(new GenericMail(
                'emails.newsletter.welcome',
                'emails.newsletter.welcome-text',
                [
                    'email'          => $email,
                    'unsubscribeUrl' => route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]),
                    'coursesUrl'     => route('courses.index'),
                ],
                'Bienvenue à la newsletter AfriCode !'
            ));

            return ['success' => true, 'message' => 'Inscription à la newsletter réussie !'];
        } catch (\Exception $e) {
            Log::error('Erreur inscription newsletter: ' . $e->getMessage());
            return ['success' => false, 'message' => "Erreur lors de l'inscription à la newsletter."];
        }
    }
}
