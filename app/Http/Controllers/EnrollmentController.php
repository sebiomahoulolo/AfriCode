<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class EnrollmentController extends Controller
{
    /**
     * Afficher la page d'inscription pour un cours
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur est déjà inscrit
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                ->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        return view('enrollment.show', compact('course'));
    }

    /**
     * Traiter l'inscription à un cours (gratuit ou payant)
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est déjà inscrit
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                ->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        try {
            DB::beginTransaction();

            if ($course->price == 0) {
                // Cours gratuit - inscription directe
                $enrollment = $this->createFreeEnrollment($user, $course);
                
                DB::commit();
                
                return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                    ->with('success', 'Félicitations ! Vous êtes maintenant inscrit au cours.');
            } else {
                // Cours payant - redirection vers la page de paiement
                $paymentMethod = $request->input('payment_method');
                
                if (!in_array($paymentMethod, ['stripe', 'fadapay'])) {
                    return back()->with('error', 'Méthode de paiement non valide.');
                }

                // Créer l'inscription en attente
                $enrollment = $this->createPendingEnrollment($user, $course);
                
                DB::commit();

                if ($paymentMethod === 'stripe') {
                    return $this->initiateStripePayment($enrollment);
                } else {
                    return $this->initiateFadaPayPayment($enrollment);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'inscription: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
        }
    }

    /**
     * Créer une inscription gratuite
     */
    private function createFreeEnrollment($user, $course)
    {
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0
        ]);

        // Créer un enregistrement de paiement gratuit
        Payment::create([
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'amount' => 0,
            'currency' => $course->currency ?? 'XOF',
            'payment_gateway' => 'free',
            'status' => 'succeeded',
            'paid_at' => now()
        ]);

        return $enrollment;
    }

    /**
     * Créer une inscription en attente de paiement
     */
    private function createPendingEnrollment($user, $course)
    {
        return Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => null, // Sera mis à jour après le paiement
            'progress_percentage' => 0
        ]);
    }

    /**
     * Initier un paiement Stripe
     */
    private function initiateStripePayment($enrollment)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $course = $enrollment->course;
            $amount = $course->price * 100; // Stripe utilise les centimes

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => strtolower($course->currency ?? 'eur'),
                'metadata' => [
                    'enrollment_id' => $enrollment->id,
                    'course_id' => $course->id,
                    'user_id' => $enrollment->user_id
                ]
            ]);

            // Créer l'enregistrement de paiement
            Payment::create([
                'user_id' => $enrollment->user_id,
                'enrollment_id' => $enrollment->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'currency' => $course->currency ?? 'EUR',
                'payment_gateway' => 'stripe',
                'transaction_id' => $paymentIntent->id,
                'status' => 'pending'
            ]);

            return view('payment.stripe', [
                'course' => $course,
                'enrollment' => $enrollment,
                'clientSecret' => $paymentIntent->client_secret,
                'publishableKey' => config('services.stripe.key')
            ]);

        } catch (Exception $e) {
            Log::error('Erreur Stripe: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement Stripe.');
        }
    }

    /**
     * Initier un paiement FadaPay
     */
    private function initiateFadaPayPayment($enrollment)
    {
        try {
            $course = $enrollment->course;
            
            // Créer l'enregistrement de paiement
            $payment = Payment::create([
                'user_id' => $enrollment->user_id,
                'enrollment_id' => $enrollment->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'currency' => $course->currency ?? 'XOF',
                'payment_gateway' => 'fadapay',
                'status' => 'pending'
            ]);

            // Générer un ID de transaction unique
            $transactionId = 'AFC_' . time() . '_' . $payment->id;
            $payment->update(['transaction_id' => $transactionId]);

            return view('payment.fadapay', [
                'course' => $course,
                'enrollment' => $enrollment,
                'payment' => $payment,
                'fadapayConfig' => [
                    'api_key' => config('services.fadapay.api_key'),
                    'merchant_id' => config('services.fadapay.merchant_id'),
                    'callback_url' => route('payment.fadapay.callback'),
                    'return_url' => route('payment.success')
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Erreur FadaPay: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement FadaPay.');
        }
    }

    /**
     * Webhook Stripe pour confirmer les paiements
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (Exception $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response('', 400);
        }

        if ($event['type'] === 'payment_intent.succeeded') {
            $paymentIntent = $event['data']['object'];
            $this->handleSuccessfulStripePayment($paymentIntent);
        }

        return response('', 200);
    }

    /**
     * Traiter un paiement Stripe réussi
     */
    private function handleSuccessfulStripePayment($paymentIntent)
    {
        $payment = Payment::where('transaction_id', $paymentIntent['id'])->first();
        
        if ($payment && $payment->status === 'pending') {
            DB::beginTransaction();
            
            try {
                // Mettre à jour le paiement
                $payment->update([
                    'status' => 'succeeded',
                    'paid_at' => now()
                ]);

                // Activer l'inscription
                $enrollment = $payment->enrollment;
                $enrollment->update(['enrolled_at' => now()]);

                DB::commit();
                
                Log::info("Paiement Stripe confirmé pour l'inscription: " . $enrollment->id);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors de la confirmation du paiement Stripe: ' . $e->getMessage());
            }
        }
    }

    /**
     * Callback FadaPay
     */
    public function fadapayCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');
        $signature = $request->input('signature');

        // Vérifier la signature (sécurité)
        $expectedSignature = hash('sha256', $transactionId . $status . config('services.fadapay.secret'));
        
        if ($signature !== $expectedSignature) {
            Log::error('FadaPay callback signature mismatch');
            return response('Invalid signature', 400);
        }

        $payment = Payment::where('transaction_id', $transactionId)->first();
        
        if ($payment && $payment->status === 'pending') {
            DB::beginTransaction();
            
            try {
                if ($status === 'success') {
                    // Mettre à jour le paiement
                    $payment->update([
                        'status' => 'succeeded',
                        'paid_at' => now()
                    ]);

                    // Activer l'inscription
                    $enrollment = $payment->enrollment;
                    $enrollment->update(['enrolled_at' => now()]);
                } else {
                    $payment->update(['status' => 'failed']);
                }

                DB::commit();
                
                Log::info("Paiement FadaPay traité: " . $transactionId . " - Status: " . $status);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors du callback FadaPay: ' . $e->getMessage());
            }
        }

        return response('OK', 200);
    }

    /**
     * Page de succès de paiement
     */
    public function paymentSuccess(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $payment = Payment::where('transaction_id', $transactionId)
            ->where('status', 'succeeded')
            ->with(['enrollment.course'])
            ->first();

        if ($payment) {
            return view('payment.success', compact('payment'));
        }

        return redirect()->route('courses.index')->with('error', 'Paiement non trouvé.');
    }

    /**
     * Page d'échec de paiement
     */
    public function paymentFailed(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        return view('payment.failed', compact('payment'));
    }

    /**
     * Traiter un paiement Stripe côté client
     */
    public function processStripePayment(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent');
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                $this->handleSuccessfulStripePayment($paymentIntent);
                
                $payment = Payment::where('transaction_id', $paymentIntentId)->first();
                return redirect()->route('payment.success', ['transaction_id' => $paymentIntentId])
                    ->with('success', 'Paiement effectué avec succès !');
            }
        } catch (Exception $e) {
            Log::error('Erreur lors du traitement du paiement Stripe: ' . $e->getMessage());
        }

        return redirect()->route('payment.failed')
            ->with('error', 'Le paiement a échoué.');
    }
}
