<?php

namespace App\Listeners;

use App\Services\RegistrationMailService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmailAfterVerification
{
    private $mailService;

    /**
     * Create the event listener.
     */
    public function __construct(RegistrationMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;

        // Envoyer l'email de bienvenue seulement après vérification
        try {
            $this->mailService->sendWelcomeEmail($user);
            \Log::info('Email de bienvenue envoyé à ' . $user->email . ' après vérification');
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email bienvenue après vérification: ' . $e->getMessage());
            // On ne bloque pas le processus si l'email échoue
        }
    }
}
