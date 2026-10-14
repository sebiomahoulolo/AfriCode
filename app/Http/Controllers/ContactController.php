<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ContactMailService;

class ContactController extends Controller
{
    private ContactMailService $mailService;

    public function __construct(ContactMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function submit(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'firstName'  => 'required|string|max:255',
            'lastName'   => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string|max:2000',
            'newsletter' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:5120',
        ]);

        try {
            // Préparer les données de contact
            $contactData = [
                'firstName' => $request->firstName,
                'lastName'  => $request->lastName,
                'email'     => $request->email,
                'subject'   => $request->subject,
                'message'   => $request->message,
            ];

            // Gérer la pièce jointe si présente
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $contactData['attachmentData'] = base64_encode(file_get_contents($file->getRealPath()));
                $contactData['attachmentName'] = $file->getClientOriginalName();
                $contactData['attachmentMime'] = $file->getMimeType();
            }

            // Envoyer l'email de contact
            $emailSent = $this->mailService->sendContactEmail($contactData);

            if (!$emailSent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi du message. Veuillez réessayer.'
                ], 500);
            }

            // Gérer l'inscription à la newsletter si cochée
            $newsletterMessage = '';
            if ($request->has('newsletter') && $request->newsletter) {
                $newsletterResult = $this->mailService->subscribeToNewsletter($request->email);
                $newsletterMessage = $newsletterResult['success'] 
                    ? ' Vous avez également été inscrit à notre newsletter !'
                    : ' ' . $newsletterResult['message'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès ! Notre équipe vous répondra dans les plus brefs délais.' . $newsletterMessage
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur formulaire contact: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.'
            ], 500);
        }
    }
}
