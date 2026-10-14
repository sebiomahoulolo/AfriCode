<?php

namespace App\Http\Controllers;

use App\Events\PaymentSucceeded;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\CourseMailService;
use App\Services\MonerooService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
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
        
        // Vérifier si l'utilisateur est déjà inscrit et activé
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNotNull('enrolled_at')
            ->first();

        if ($activeEnrollment) {
            // Vérifier s'il y a un paiement réussi pour les cours payants
            if ($course->price > 0) {
                $successfulPayment = $activeEnrollment->payments()
                    ->where('status', 'succeeded')
                    ->first();
                    
                if ($successfulPayment) {
                    return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                        ->with('info', 'Vous êtes déjà inscrit à ce cours.');
                } else {
                    // NOUVELLE LOGIQUE : Permettre de refaire un paiement
                    // L'utilisateur peut accéder à la page de paiement pour réessayer
                    Log::info('Accès page de paiement pour nouvelle tentative', [
                        'enrollment_id' => $activeEnrollment->id,
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'enrolled_at' => $activeEnrollment->enrolled_at
                    ]);
                    
                    // Désactiver l'inscription pour permettre un nouveau paiement
                    $activeEnrollment->update(['enrolled_at' => null]);
                }
            } else {
                // Cours gratuit, redirection directe
                return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                    ->with('info', 'Vous êtes déjà inscrit à ce cours.');
            }
        }

        // Vérifier s'il y a une inscription en attente (pour réutiliser l'inscription)
        $pendingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNull('enrolled_at')
            ->first();

        // Ne pas passer les informations sur les paiements échoués pour une expérience "nouvelle"
        return view('enrollment.show', compact('course'));
    }

    /**
     * Traiter l'inscription à un cours (gratuit ou payant)
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();
        
        Log::info('Tentative de paiement initiée', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_title' => $course->title,
            'payment_method' => $request->input('payment_method')
        ]);

        // NOUVELLE LOGIQUE : Permettre plusieurs tentatives de paiement
        // Vérifier s'il y a une inscription avec paiement réussi
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment && $course->price > 0) {
            $successfulPayment = $existingEnrollment->payments()
                ->where('status', 'succeeded')
                ->first();
                
            if ($successfulPayment) {
                // L'utilisateur a déjà un paiement réussi, rediriger vers le cours
                Log::info('Utilisateur avec paiement réussi', [
                    'enrollment_id' => $existingEnrollment->id,
                    'payment_id' => $successfulPayment->id
                ]);
                
                return redirect()->route('apprenant.course.access', ['courseId' => $course->id])
                    ->with('info', 'Vous êtes déjà inscrit à ce cours.');
            }
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
                
                if (!in_array($paymentMethod, ['stripe', 'moneroo'])) {
                    return back()->with('error', 'Méthode de paiement non valide.');
                }

        // NOUVELLE LOGIQUE : Permettre plusieurs tentatives de paiement
        // Chercher une inscription existante (peu importe le statut)
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            // Réutiliser l'inscription existante
            $enrollment = $existingEnrollment;
            
            // Si l'inscription est activée mais sans paiement réussi, la désactiver
            if ($enrollment->enrolled_at) {
                $successfulPayment = $enrollment->payments()
                    ->where('status', 'succeeded')
                    ->first();
                    
                if (!$successfulPayment) {
                    Log::info('Désactivation inscription pour nouvelle tentative de paiement', [
                        'enrollment_id' => $enrollment->id,
                        'user_id' => $user->id,
                        'course_id' => $course->id
                    ]);
                    
                    $enrollment->update(['enrolled_at' => null]);
                }
            }
        } else {
            // Créer une nouvelle inscription
            $enrollment = $this->createPendingEnrollment($user, $course);
        }
                
                DB::commit();

                if ($paymentMethod === 'stripe') {
                    return $this->initiateStripePayment($enrollment);
                } else {
                    return $this->initiateMonerooPayment($enrollment);
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
        DB::beginTransaction();
        
        try {
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
            'payable_id' => $course->id,
            'payable_type' => 'App\\Models\\Course',
            'amount' => 0,
            'currency' => $course->currency ?? 'XOF',
            'payment_gateway' => 'free',
            'payment_method' => 'free',
            'status' => 'succeeded',
            'paid_at' => now(),
            'transaction_id' => 'FREE_' . uniqid()
        ]);

            DB::commit();

            // Email de confirmation (hors transaction pour ne pas bloquer si SMTP échoue)
            try {
                $payment = $enrollment->payments()->where('status', 'succeeded')->latest()->first();
                (new CourseMailService())->sendEnrollmentConfirmedToStudent($enrollment->load(['user', 'course.formateur']), $payment);
            } catch (\Throwable $e) {
                Log::error('Erreur email confirmation inscription gratuite: ' . $e->getMessage(), ['enrollment_id' => $enrollment->id]);
            }

        return $enrollment;

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de l\'inscription gratuite: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Créer une inscription en attente de paiement
     */
    private function createPendingEnrollment($user, $course)
    {
        // Vérifier s'il y a déjà une inscription en attente pour ce cours
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNull('enrolled_at')
            ->first();
            
        if ($existingEnrollment) {
            return $existingEnrollment;
        }
        
        return Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => null, // IMPORTANT: Toujours null jusqu'au paiement réussi
            'progress_percentage' => 0
        ]);
    }

    /**
     * Initier un paiement Stripe
     */
    private function initiateStripePayment($enrollment)
    {
        try {
            $course = $enrollment->course;
            $user = Auth::user();
            
            Log::info('Initialisation paiement Stripe', [
                'enrollment_id' => $enrollment->id,
                'user_id' => $user->id,
                'course_id' => $course->id,
                'course_price' => $course->price,
                'course_currency' => $course->currency
            ]);
            
            // Nettoyer les paiements échoués existants pour cet utilisateur et ce cours
            $this->cleanupFailedPaymentsForUser($user->id, $course->id);
            
            // Configuration Stripe depuis .env
            $stripeSecretKey = config('services.stripe.secret');
            $stripePublishableKey = config('services.stripe.key');

            // Configurer Stripe
            \Stripe\Stripe::setApiKey($stripeSecretKey);
            
            // Convertir le prix en centimes (Stripe utilise les centimes)
            $amount = (int) round((float) $course->price * 100);
            
            // Créer un PaymentIntent Stripe
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => strtolower($course->currency ?? 'eur'),
                'metadata' => [
                    'enrollment_id' => $enrollment->id,
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                    'course_title' => $course->title
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Créer l'enregistrement de paiement en base
            $payment = Payment::create([
                'user_id' => $enrollment->user_id,
                'enrollment_id' => $enrollment->id,
                'payable_id' => $course->id,
                'payable_type' => 'App\\Models\\Course',
                'amount' => $course->price,
                'currency' => $course->currency ?? 'EUR',
                'payment_gateway' => 'stripe',
                'payment_method' => 'stripe',
                'payment_date' => now(),
                'transaction_id' => $paymentIntent->id,
                'status' => 'pending' // IMPORTANT: Toujours pending jusqu'à confirmation
            ]);
            
            // S'assurer que l'inscription reste en attente
            $enrollment->update(['enrolled_at' => null]);

            Log::info('PaymentIntent Stripe créé', [
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $amount,
                'currency' => $course->currency
            ]);

            return view('payment.stripe', [
                'course' => $course,
                'enrollment' => $enrollment,
                'payment' => $payment,
                'clientSecret' => $paymentIntent->client_secret,
                'publishableKey' => $stripePublishableKey
            ]);

        } catch (Exception $e) {
            Log::error('Erreur Stripe: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement Stripe.');
        }
    }

    /**
     * Initier un paiement Moneroo
     */
    private function initiateMonerooPayment($enrollment)
    {
        try {
            $course = $enrollment->course;
            $user   = Auth::user();

            Log::info('Initialisation paiement Moneroo', [
                'enrollment_id' => $enrollment->id,
                'user_id'       => $user->id,
                'course_id'     => $course->id,
                'amount'        => $course->price,
            ]);

            $this->cleanupFailedPaymentsForUser($user->id, $course->id);

            // Enregistrement en base avec un ID temporaire
            $payment = Payment::create([
                'user_id'          => $enrollment->user_id,
                'enrollment_id'    => $enrollment->id,
                'payable_id'       => $course->id,
                'payable_type'     => 'App\\Models\\Course',
                'amount'           => $course->price,
                'currency'         => $course->currency ?? 'XOF',
                'payment_gateway'  => 'moneroo',
                'payment_method'   => 'moneroo',
                'payment_date'     => now(),
                'transaction_id'   => 'TEMP_' . uniqid(),
                'status'           => 'pending',
            ]);

            $enrollment->update(['enrolled_at' => null]);

            // URL de retour : Moneroo y ajoute automatiquement ?transaction_id=XXX
            $returnUrl = route('payment.moneroo.return', ['payment_id' => $payment->id]);

            $monerooResponse = (new MonerooService())->initPayment([
                'amount'      => (int) round((float) $course->price),
                'currency'    => $course->currency ?? 'XOF',
                'description' => 'Achat du cours : ' . $course->title,
                'return_url'  => $returnUrl,
                'customer'    => [
                    'email'      => $user->email,
                    'first_name' => $user->first_name ?? ($user->name ?? 'Apprenant'),
                    'last_name'  => $user->last_name ?? '',
                ],
                'metadata'    => [
                    'payment_id'    => $payment->id,
                    'enrollment_id' => $enrollment->id,
                    'course_id'     => $course->id,
                    'user_id'       => $user->id,
                ],
            ]);

            // $monerooResponse contient checkout_url et id (transaction ID Moneroo)
            $checkoutUrl   = $monerooResponse->checkout_url ?? null;
            $monerooTxnId  = $monerooResponse->id ?? null;

            if (!$checkoutUrl || !$monerooTxnId) {
                Log::error('Moneroo: réponse invalide', (array) $monerooResponse);
                $payment->delete();
                return back()->with('error', 'Impossible de générer le lien de paiement Moneroo.');
            }

            // Sauvegarder l'ID Moneroo en base
            $payment->update(['transaction_id' => $monerooTxnId]);

            Log::info('Redirection vers Moneroo', [
                'moneroo_txn_id' => $monerooTxnId,
                'checkout_url'   => $checkoutUrl,
                'payment_id'     => $payment->id,
            ]);

            return redirect($checkoutUrl);

        } catch (Exception $e) {
            Log::error('Erreur Moneroo init: ' . $e->getMessage());
            if (isset($payment)) {
                $payment->delete();
            }
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement Moneroo. Veuillez réessayer.');
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

                // Activer l'inscription seulement si le paiement est valide
                $enrollment = $payment->enrollment;
                if ($enrollment && !$enrollment->enrolled_at) {
                $enrollment->update(['enrolled_at' => now()]);
                    Log::info("Paiement Stripe confirmé pour l'inscription: " . $enrollment->id);
                } else {
                    Log::warning("Tentative d'activation d'une inscription déjà active ou inexistante: " . ($enrollment->id ?? 'N/A'));
                }

                DB::commit();

                $payment->refresh();
                if ($payment->enrollment && $payment->enrollment->enrolled_at) {
                    event(new PaymentSucceeded($payment));

                    // Email de confirmation d'inscription
                    try {
                        (new CourseMailService())->sendEnrollmentConfirmedToStudent(
                            $payment->enrollment->load(['user', 'course.formateur']),
                            $payment
                        );
                    } catch (\Throwable $e) {
                        Log::error('Erreur email confirmation Stripe: ' . $e->getMessage());
                    }
                }

            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors de la confirmation du paiement Stripe: ' . $e->getMessage());
            }
        }
    }

    /**
     * Retour Moneroo après paiement — Moneroo redirige ici avec ?transaction_id=XXX
     */
    public function monerooReturn(Request $request)
    {
        // payment_id = notre ID interne (passé dans return_url)
        // transaction_id = l'ID Moneroo (ajouté automatiquement par Moneroo en querystring)
        $paymentId      = $request->input('payment_id');
        $monerooTxnId   = $request->input('transaction_id');

        Log::info('Retour Moneroo', [
            'payment_id'      => $paymentId,
            'moneroo_txn_id'  => $monerooTxnId,
            'user_id'         => Auth::id(),
        ]);

        $payment = Payment::with(['enrollment.course', 'enrollment.user'])->find($paymentId);

        if (!$payment) {
            Log::error('Moneroo return: paiement introuvable', ['payment_id' => $paymentId]);
            return redirect()->route('courses.index')->with('error', 'Paiement introuvable.');
        }

        // Vérification sécurité : si l'utilisateur est connecté, le paiement doit lui appartenir
        if (Auth::check() && $payment->enrollment && $payment->enrollment->user_id !== Auth::id()) {
            Log::warning('Moneroo return: accès non autorisé', [
                'payment_id' => $paymentId,
                'auth_user'  => Auth::id(),
                'owner_id'   => $payment->enrollment->user_id,
            ]);
            abort(403, 'Accès non autorisé à ce paiement.');
        }

        // Ne pas retraiter un paiement déjà validé
        if ($payment->status === 'succeeded') {
            return redirect()->route('payment.success', ['transaction_id' => $payment->id]);
        }

        // Vérification du statut via l'API Moneroo
        try {
            $txnIdToVerify = $monerooTxnId ?? $payment->transaction_id;
            $verified = (new MonerooService())->verifyPayment($txnIdToVerify);

            $monerooStatus = $verified->status ?? null;

            Log::info('Vérification Moneroo', [
                'payment_id'     => $payment->id,
                'moneroo_txn_id' => $txnIdToVerify,
                'status'         => $monerooStatus,
            ]);

            if ($monerooStatus === 'success') {
                DB::beginTransaction();
                try {
                    // Verrou pessimiste pour éviter les double-traitements
                    $payment = Payment::lockForUpdate()->find($payment->id);
                    if ($payment->status === 'succeeded') {
                        DB::rollBack();
                        return redirect()->route('payment.success', ['transaction_id' => $payment->id]);
                    }
                    $payment->status = 'succeeded';
                    $payment->paid_at = now();
                    $payment->transaction_id = $txnIdToVerify;
                    $payment->payment_details = array_merge($payment->payment_details ?? [], [
                        'moneroo_status' => $monerooStatus,
                    ]);
                    $payment->save();

                    $enrollment = $payment->enrollment;
                    if ($enrollment && !$enrollment->enrolled_at) {
                        $enrollment->update(['enrolled_at' => now()]);
                        Log::info('Inscription activée (Moneroo)', ['enrollment_id' => $enrollment->id]);
                    }

                    DB::commit();

                    $payment->refresh();
                    if ($payment->enrollment && $payment->enrollment->enrolled_at) {
                        event(new PaymentSucceeded($payment));

                        try {
                            (new CourseMailService())->sendEnrollmentConfirmedToStudent(
                                $payment->enrollment->load(['user', 'course.formateur']),
                                $payment
                            );
                        } catch (\Throwable $e) {
                            Log::error('Erreur email confirmation Moneroo: ' . $e->getMessage());
                        }
                    }

                    return redirect()->route('payment.success', ['transaction_id' => $payment->id])
                        ->with('success', 'Paiement réussi ! Votre inscription est confirmée.');

                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Erreur activation inscription Moneroo: ' . $e->getMessage());
                    return redirect()->route('payment.failed', ['transaction_id' => $payment->id])
                        ->with('error', 'Paiement confirmé mais erreur lors de l\'activation.');
                }

            } elseif (in_array($monerooStatus, ['failed', 'cancelled'])) {
                $payment->update([
                    'status'          => 'failed',
                    'payment_details' => array_merge($payment->payment_details ?? [], [
                        'moneroo_status' => $monerooStatus,
                    ]),
                ]);
                if ($payment->enrollment) {
                    $payment->enrollment->update(['enrolled_at' => null]);
                }

                return redirect()->route('payment.failed', ['transaction_id' => $payment->id])
                    ->with('error', 'Paiement ' . ($monerooStatus === 'cancelled' ? 'annulé' : 'échoué') . '. Veuillez réessayer.');

            } else {
                // Statut pending/initiated — paiement non encore finalisé
                Log::info('Moneroo paiement en attente', ['status' => $monerooStatus, 'payment_id' => $payment->id]);
                return redirect()->route('payment.failed', ['transaction_id' => $payment->id])
                    ->with('info', 'Votre paiement est en cours de traitement. Vous serez notifié dès confirmation.');
            }

        } catch (Exception $e) {
            Log::error('Erreur vérification Moneroo: ' . $e->getMessage(), ['payment_id' => $payment->id]);
            return redirect()->route('payment.failed', ['transaction_id' => $payment->id])
                ->with('error', 'Impossible de vérifier le statut du paiement. Contactez le support si vous avez été débité.');
        }
    }

    /**
     * Page de succès de paiement
     */
    public function paymentSuccess(Request $request)
    {
        $transactionId = $request->input('transaction_id');

        $payment = Payment::where('id', $transactionId)
            ->orWhere('transaction_id', $transactionId)
            ->with(['enrollment.course'])
            ->first();

        if (!$payment) {
            return redirect()->route('courses.index')->with('error', 'Paiement non trouvé.');
        }

        // Si le paiement Moneroo est encore en attente à l'arrivée ici, on re-vérifie
        if ($payment->payment_gateway === 'moneroo' && $payment->status === 'pending') {
            try {
                $verified = (new MonerooService())->verifyPayment($payment->transaction_id);
                if (($verified->status ?? null) === 'success') {
                    DB::transaction(function () use ($payment) {
                        $payment->update(['status' => 'succeeded', 'paid_at' => now()]);
                        if ($payment->enrollment && !$payment->enrollment->enrolled_at) {
                            $payment->enrollment->update(['enrolled_at' => now()]);
                        }
                    });
                    $payment->refresh();
                    if ($payment->enrollment && $payment->enrollment->enrolled_at) {
                        event(new PaymentSucceeded($payment));
                    }
                }
            } catch (Exception $e) {
                Log::error('Re-vérification Moneroo dans paymentSuccess: ' . $e->getMessage());
            }
        }

        if ($payment->status === 'succeeded') {
            return view('payment.success', compact('payment'));
        }

        return redirect()->route('payment.failed', ['transaction_id' => $transactionId])
            ->with('error', 'Paiement non confirmé.');
    }

    /**
     * Page d'échec de paiement
     */
    public function paymentFailed(Request $request)
    {
        $transactionId = $request->input('transaction_id');

        if ($transactionId) {
            $payment = Payment::where('transaction_id', $transactionId)->with(['enrollment.course'])->first();

            if ($payment) {
                // Marquer le paiement comme échoué s'il est encore en attente
                if ($payment->status === 'pending') {
                    $payment->update([
                        'status' => 'failed',
                        'payment_details' => array_merge($payment->payment_details ?? [], [
                            'failed_at' => now(),
                            'failure_reason' => 'user_cancelled_or_timeout'
                        ])
                    ]);
                    
                    // S'assurer que l'inscription reste en attente
                    if ($payment->enrollment) {
                        $payment->enrollment->update(['enrolled_at' => null]);
                    }
                }

                // Toujours rediriger vers la page d'inscription pour permettre le choix de méthode de paiement
                if ($payment->enrollment && $payment->enrollment->course) {
                    return redirect()->route('enrollment.show', $payment->enrollment->course)
                        ->with('info', 'Choisissez votre méthode de paiement pour continuer.');
                }

                return view('payment.failed', compact('payment'));
            }
        }

        $payment = null;
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

    /**
     * Nettoyer les paiements échoués anciens (plus de 24h)
     */
    public function cleanupFailedPayments()
    {
        $cutoffTime = now()->subHours(24);
        
        $failedPayments = Payment::where('status', 'failed')
            ->where('created_at', '<', $cutoffTime)
            ->whereDoesntHave('enrollment', function($query) {
                $query->whereNotNull('enrolled_at');
            })
            ->get();

        foreach ($failedPayments as $payment) {
            // Supprimer l'inscription en attente si elle n'a pas d'autres paiements
            if ($payment->enrollment && !$payment->enrollment->enrolled_at) {
                $otherPayments = Payment::where('enrollment_id', $payment->enrollment_id)
                    ->where('id', '!=', $payment->id)
                    ->where('status', '!=', 'failed')
                    ->exists();
                    
                if (!$otherPayments) {
                    $payment->enrollment->delete();
                }
            }
            
            // Supprimer le paiement échoué
            $payment->delete();
        }

        Log::info('Nettoyage des paiements échoués', ['count' => $failedPayments->count()]);
        
        return response()->json(['cleaned' => $failedPayments->count()]);
    }

    /**
     * Nettoyer les inscriptions en attente orphelines
     */
    public function cleanupOrphanedEnrollments()
    {
        $cutoffTime = now()->subHours(24);
        
        // Trouver les inscriptions en attente sans paiements récents
        $orphanedEnrollments = Enrollment::whereNull('enrolled_at')
            ->where('created_at', '<', $cutoffTime)
            ->whereDoesntHave('payments', function($query) use ($cutoffTime) {
                $query->where('created_at', '>', $cutoffTime)
                      ->where('status', '!=', 'failed');
            })
            ->get();

        foreach ($orphanedEnrollments as $enrollment) {
            // Supprimer les paiements échoués associés
            $enrollment->payments()->where('status', 'failed')->delete();
            // Supprimer l'inscription orpheline
            $enrollment->delete();
        }

        Log::info('Nettoyage des inscriptions orphelines', ['count' => $orphanedEnrollments->count()]);
        
        return response()->json(['cleaned' => $orphanedEnrollments->count()]);
    }

    /**
     * Callback PayPal - Succès
     */
    public function paypalSuccess(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        Log::info('Callback PayPal Success', [
            'payment_id' => $paymentId,
            'payment_status' => $payment->status,
            'all_params' => $request->all()
        ]);

        // Mettre à jour le paiement comme réussi
        $payment->update([
            'status' => 'succeeded',
            'paid_at' => now(),
            'payment_details' => array_merge($payment->payment_details ?? [], [
                'paypal_success' => true,
                'callback_data' => $request->all()
            ])
        ]);

        // Activer l'inscription
        $enrollment = $payment->enrollment;
        if ($enrollment && !$enrollment->enrolled_at) {
            $enrollment->update(['enrolled_at' => now()]);
            Log::info("Inscription activée après paiement PayPal réussi", [
                'enrollment_id' => $enrollment->id,
                'payment_id' => $payment->id
            ]);
        }

        return redirect()->route('payment.success', ['transaction_id' => $payment->id])
            ->with('success', 'Paiement PayPal réussi et inscription activée ✅');
    }

    /**
     * Callback PayPal - Annulation
     */
    public function paypalCancel(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        Log::info('Callback PayPal Cancel', [
            'payment_id' => $paymentId,
            'all_params' => $request->all()
        ]);

        // Marquer le paiement comme annulé
        $payment->update([
            'status' => 'cancelled',
            'payment_details' => array_merge($payment->payment_details ?? [], [
                'paypal_cancelled' => true,
                'cancelled_at' => now(),
                'callback_data' => $request->all()
            ])
        ]);

        return redirect()->route('payment.failed', ['transaction_id' => $payment->id])
            ->with('error', 'Paiement PayPal annulé. Vous pouvez réessayer quand vous le souhaitez.');
    }

    /**
     * Webhook PayPal
     */
    public function paypalWebhook(Request $request)
    {
        Log::info('Webhook PayPal reçu', [
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        // Ici vous pouvez ajouter la logique de vérification du webhook PayPal
        // Pour l'instant, on log juste les données reçues
        
        return response()->json(['status' => 'received']);
    }

    /**
     * Nettoyer les paiements échoués pour un utilisateur et un cours spécifiques
     */
    private function cleanupFailedPaymentsForUser($userId, $courseId)
    {
        try {
            // Trouver tous les paiements échoués pour cet utilisateur et ce cours
            $failedPayments = Payment::where('user_id', $userId)
                ->where('payable_id', $courseId)
                ->where('payable_type', 'App\\Models\\Course')
                ->where('status', 'failed')
                ->get();

            foreach ($failedPayments as $payment) {
                // Supprimer l'inscription en attente si elle n'a pas d'autres paiements actifs
                if ($payment->enrollment && !$payment->enrollment->enrolled_at) {
                    $otherActivePayments = Payment::where('enrollment_id', $payment->enrollment_id)
                        ->where('id', '!=', $payment->id)
                        ->whereIn('status', ['pending', 'succeeded'])
                        ->exists();
                        
                    if (!$otherActivePayments) {
                        $payment->enrollment->delete();
                    }
                }
                
                // Supprimer le paiement échoué
                $payment->delete();
            }

            if ($failedPayments->count() > 0) {
                Log::info('Nettoyage des paiements échoués pour utilisateur', [
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'count' => $failedPayments->count()
                ]);
            }

        } catch (Exception $e) {
            Log::error('Erreur lors du nettoyage des paiements échoués: ' . $e->getMessage());
            // Ne pas faire échouer le processus principal pour une erreur de nettoyage
        }
    }
}
