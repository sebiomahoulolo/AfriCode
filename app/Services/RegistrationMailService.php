<?php

namespace App\Services;

use App\Mail\GenericMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegistrationMailService
{
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            $data = [
                'user'         => $user,
                'dashboardUrl' => $user->role === 'apprenant'
                    ? route('apprenant.dashboard')
                    : route('formateur.dashboard'),
                'roleText'     => $user->role === 'apprenant' ? 'Apprenant' : 'Formateur',
            ];

            Mail::to($user->email)->queue(new GenericMail(
                'emails.registration.welcome',
                'emails.registration.welcome-text',
                $data,
                'Bienvenue sur AfriCode, ' . $user->first_name . ' !'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email bienvenue: ' . $e->getMessage(), ['user_id' => $user->id]);
            return false;
        }
    }
}
