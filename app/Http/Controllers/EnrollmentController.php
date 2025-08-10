<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\FedaPayService;
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
            // Si cours payant, vérifier le statut du paiement
            if ($course->price > 0) {
                $payment = Payment::where('enrollment_id', $existingEnrollment->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($payment && $payment->status === 'completed') {
                    // Paiement complété, rediriger vers le cours
                    return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                        ->with('info', 'Vous êtes déjà inscrit à ce cours.');
                } elseif ($payment && in_array($payment->status, ['pending', 'processing'])) {
                    // Paiement en cours, afficher le statut
                    return view('payment.status', [
                        'course' => $course,
                        'payment' => $payment,
                        'enrollment' => $existingEnrollment
                    ]);
                } else {
                    // Pas de paiement ou paiement échoué, permettre un nouveau paiement
                    return view('enrollment.show', compact('course'));
                }
            } else {
                // Cours gratuit, rediriger directement
                return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                    ->with('info', 'Vous êtes déjà inscrit à ce cours.');
            }
        }

        return view('enrollment.show', compact('course'));
    }

    /**
     * Traiter l'inscription à un cours (gratuit ou payant)
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        Log::info('Enrollment store method called', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'request_data' => $request->all()
        ]);

        // Vérifier si l'utilisateur est déjà inscrit
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            Log::info('User already enrolled, redirecting', [
                'enrollment_id' => $existingEnrollment->id
            ]);
            
            return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                ->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        try {
            DB::beginTransaction();

            if ($course->price == 0) {
                Log::info('Processing free course enrollment');
                
                // Cours gratuit - inscription directe
                $enrollment = $this->createFreeEnrollment($user, $course);
                
                DB::commit();
                
                return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                    ->with('success', 'Félicitations ! Vous êtes maintenant inscrit au cours.');
            } else {
                Log::info('Processing paid course enrollment');
                
                // Cours payant - redirection vers la page de paiement
                $paymentMethod = $request->input('payment_method');
                
                Log::info('Payment method selected', ['payment_method' => $paymentMethod]);
                
                if (!in_array($paymentMethod, ['stripe', 'fedapay'])) {
                    Log::error('Invalid payment method', ['payment_method' => $paymentMethod]);
                    return back()->with('error', 'Méthode de paiement non valide.');
                }

                // Créer l'inscription en attente
                $enrollment = $this->createPendingEnrollment($user, $course);
                
                Log::info('Pending enrollment created', ['enrollment_id' => $enrollment->id]);
                
                DB::commit();

                if ($paymentMethod === 'stripe') {
                    Log::info('Redirecting to Stripe payment');
                    return $this->initiateStripePayment($enrollment);
                } else {
                    Log::info('Redirecting to FedaPay payment');
                    return $this->initiateFedaPayPayment($enrollment);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error during enrollment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
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
            // 'course_id' => $course->id,
            'payable_id' => $course->id,
            'payable_type' => 'App\\Models\\Course',
            'amount' => 0,
            'currency' => $course->currency ?? 'XOF',
            'payment_gateway' => 'free',
            'payment_method' => 'free',
            'status' => 'completed',
            'paid_at' => now(),
            'transaction_id' => 'FREE_' . uniqid()
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
                // 'course_id' => $course->id,
                'payable_id' => $course->id,
                'payable_type' => 'App\\Models\\Course',
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
     * Initier un paiement FedaPay
     */
    private function initiateFedaPayPayment($enrollment)
    {
        Log::info('Initiating FedaPay payment', [
            'enrollment_id' => $enrollment->id,
            'course_id' => $enrollment->course_id,
            'user_id' => $enrollment->user_id
        ]);

        try {
            $course = $enrollment->course;
            $user = $enrollment->user;
            $fedaPayService = new FedaPayService();

            Log::info('Course and user loaded', [
                'course_title' => $course->title,
                'course_price' => $course->price,
                'user_email' => $user->email
            ]);

            // Créer d'abord l'enregistrement de paiement
            $payment = Payment::create([
                'user_id' => $enrollment->user_id,
                'enrollment_id' => $enrollment->id,
                'payable_id' => $course->id,
                'payable_type' => 'App\\Models\\Course',
                'amount' => $course->price,
                'currency' => 'EUR',
                'payment_gateway' => 'fedapay',
                'status' => 'pending',
                'transaction_id' => 'FEDAPAY_' . uniqid()
            ]);

            Log::info('Payment record created', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id
            ]);

            // Créer la transaction via le service FedaPay
            $transaction = $fedaPayService->createTransaction($payment, $user, $course);

            Log::info('FedaPay service called', [
                'transaction_success' => $transaction !== null,
                'transaction_data' => $transaction
            ]);

            if (!$transaction) {
                Log::error('FedaPay transaction creation failed');
                return back()->with('error', 'Erreur lors de l\'initialisation du paiement FedaPay.');
            }

            // Mettre à jour le paiement avec l'ID de transaction FedaPay
            $payment->update([
                'gateway_transaction_id' => $transaction['id'] ?? $transaction['reference'] ?? null
            ]);

            Log::info('Redirecting to FedaPay payment page');

            return view('payment.fedapay', [
                'course' => $course,
                'enrollment' => $enrollment,
                'payment' => $payment,
                'transaction' => $transaction,
                'checkoutConfig' => [
                    'public_key' => config('services.fedapay.public_key'),
                    'environment' => config('services.fedapay.environment'),
                    'transaction' => [
                        'id' => $transaction['id'] ?? $transaction['reference'],
                        'amount' => $payment->converted_amount ?: $payment->amount,
                        'description' => "Achat du cours: {$course->title}",
                        'currency' => [
                            'iso' => $payment->converted_currency ?: 'XOF'
                        ]
                    ],
                    'customer' => [
                        'email' => $user->email,
                        'firstname' => $user->first_name ?? explode(' ', $user->name)[0] ?? 'Client',
                        'lastname' => $user->last_name ?? explode(' ', $user->name)[1] ?? '',
                    ]
                ]
            ]);

        } catch (Exception $e) {
            Log::error('FedaPay payment initiation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement FedaPay: ' . $e->getMessage());
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
                    'status' => 'completed',
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
     * Webhook FedaPay pour confirmer les paiements
     */
    public function fedapayWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('X-FedaPay-Signature');
            
            $fedaPayService = new FedaPayService();
            
            // Valider la signature
            if (!$fedaPayService->validateWebhookSignature($payload, $signature)) {
                Log::error('FedaPay webhook signature invalide');
                return response('Invalid signature', 400);
            }

            $data = json_decode($payload, true);
            
            if ($fedaPayService->processWebhook($data)) {
                return response('OK', 200);
            } else {
                return response('Error processing webhook', 400);
            }

        } catch (Exception $e) {
            Log::error('Erreur webhook FedaPay: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Page de succès de paiement
     */
    public function paymentSuccess(Request $request)
    {
        $paymentId = $request->input('payment');
        $transactionId = $request->input('transaction_id');
        
        $payment = null;
        
        if ($paymentId) {
            $payment = Payment::where('id', $paymentId)
                ->where('status', 'completed')
                ->with(['enrollment.course'])
                ->first();
        } elseif ($transactionId) {
            $payment = Payment::where('transaction_id', $transactionId)
                ->orWhere('gateway_transaction_id', $transactionId)
                ->where('status', 'completed')
                ->with(['enrollment.course'])
                ->first();
        }

        if ($payment && $payment->enrollment && $payment->enrollment->course) {
            return redirect()->route('apprenant.course.access', ['courseId' => $payment->enrollment->course->id])
                ->with('success', 'Paiement réussi ! Vous pouvez maintenant accéder à votre formation.');
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
     * Vérifier le statut d'un paiement via API
     */
    public function checkPaymentStatus(Payment $payment)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire du paiement
        if ($payment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        return response()->json([
            'id' => $payment->id,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'transaction_id' => $payment->transaction_id,
            'updated_at' => $payment->updated_at->toISOString()
        ]);
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
