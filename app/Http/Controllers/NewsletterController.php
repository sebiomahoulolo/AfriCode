<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Validation\ValidationException;
use App\Services\NewsletterMailService;

class NewsletterController extends Controller
{
    private $mailService;

    public function __construct(NewsletterMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:newsletter_subscribers,email',
            ]);

            $subscriber = NewsletterSubscriber::create([
                'email' => $request->email,
            ]);

            // Envoyer l'email de bienvenue
            $emailSent = $this->mailService->sendWelcomeEmail($subscriber);

            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre inscription à la newsletter ! Un email de confirmation vous a été envoyé.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['email'][0] ?? 'Une erreur de validation est survenue.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.'
            ], 500);
        }
    }

    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Lien de désabonnement invalide.');
        }

        $subscriber->delete();

        return redirect()->route('home')->with('success', 'Vous avez été désabonné de la newsletter avec succès.');
    }
}
