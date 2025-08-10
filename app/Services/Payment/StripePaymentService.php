<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Services\Payment\BasePaymentService;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Webhook;
use Exception;

class StripePaymentService extends BasePaymentService
{
    public function __construct($gateway)
    {
        parent::__construct($gateway);
        Stripe::setApiKey($this->config['secret_key'] ?? '');
    }

    public function initiatePayment(Payment $payment): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => intval($payment->total_amount * 100), // Stripe utilise les centimes
                'currency' => strtolower($payment->currency),
                'payment_method_types' => ['card'],
                'metadata' => [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'course_id' => $payment->course_id,
                    'transaction_id' => $payment->transaction_id
                ],
                'receipt_email' => $payment->user->email,
                'description' => "Achat du cours: {$payment->course->title}",
            ]);

            $payment->update([
                'external_transaction_id' => $paymentIntent->id,
                'status' => Payment::STATUS_PROCESSING,
                'metadata' => [
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret
                ]
            ]);

            return $this->formatResponse(true, 'Payment intent créé avec succès', [
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'publishable_key' => $this->config['publishable_key'],
                'amount' => $payment->total_amount,
                'currency' => $payment->currency
            ]);

        } catch (Exception $e) {
            $this->logError('Erreur lors de la création du payment intent', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            $payment->markAsFailed($e->getMessage());

            return $this->formatResponse(false, 'Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
        }
    }

    public function checkPaymentStatus(Payment $payment): array
    {
        try {
            $paymentIntentId = $payment->metadata['stripe_payment_intent_id'] ?? null;
            
            if (!$paymentIntentId) {
                return $this->formatResponse(false, 'ID PaymentIntent manquant');
            }

            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            switch ($paymentIntent->status) {
                case 'succeeded':
                    $payment->markAsCompleted($paymentIntentId, [
                        'stripe_payment_method' => $paymentIntent->payment_method,
                        'stripe_charges' => $paymentIntent->charges->data
                    ]);
                    return $this->formatResponse(true, 'Paiement complété avec succès');

                case 'processing':
                    $payment->update(['status' => Payment::STATUS_PROCESSING]);
                    return $this->formatResponse(true, 'Paiement en cours de traitement');

                case 'requires_payment_method':
                case 'requires_confirmation':
                    return $this->formatResponse(false, 'Le paiement nécessite une action supplémentaire');

                case 'canceled':
                    $payment->update(['status' => Payment::STATUS_CANCELLED]);
                    return $this->formatResponse(false, 'Paiement annulé');

                default:
                    return $this->formatResponse(false, 'Statut de paiement inconnu: ' . $paymentIntent->status);
            }

        } catch (Exception $e) {
            $this->logError('Erreur lors de la vérification du statut', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return $this->formatResponse(false, 'Erreur lors de la vérification: ' . $e->getMessage());
        }
    }

    public function handleWebhook(array $payload): array
    {
        try {
            $webhookSecret = $this->config['webhook_secret'] ?? '';
            $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

            $event = Webhook::constructEvent(
                json_encode($payload),
                $sigHeader,
                $webhookSecret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    return $this->handlePaymentIntentSucceeded($event->data->object);

                case 'payment_intent.payment_failed':
                    return $this->handlePaymentIntentFailed($event->data->object);

                case 'charge.dispute.created':
                    return $this->handleChargeDispute($event->data->object);

                default:
                    $this->logInfo('Webhook non géré', ['event_type' => $event->type]);
                    return $this->formatResponse(true, 'Webhook reçu mais non traité');
            }

        } catch (Exception $e) {
            $this->logError('Erreur lors du traitement du webhook', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return $this->formatResponse(false, 'Erreur webhook: ' . $e->getMessage());
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent): array
    {
        $payment = Payment::where('external_transaction_id', $paymentIntent->id)->first();

        if (!$payment) {
            $this->logError('Payment non trouvé pour le PaymentIntent', [
                'payment_intent_id' => $paymentIntent->id
            ]);
            return $this->formatResponse(false, 'Paiement non trouvé');
        }

        $payment->markAsCompleted($paymentIntent->id, [
            'stripe_payment_method' => $paymentIntent->payment_method,
            'webhook_received_at' => now()
        ]);

        // Activer l'inscription au cours
        if ($payment->course && $payment->user) {
            $payment->user->courses()->syncWithoutDetaching([
                $payment->course->id => [
                    'enrolled_at' => now(),
                    'progress_percentage' => 0
                ]
            ]);
        }

        return $this->formatResponse(true, 'Paiement confirmé par webhook');
    }

    private function handlePaymentIntentFailed($paymentIntent): array
    {
        $payment = Payment::where('external_transaction_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->markAsFailed($paymentIntent->last_payment_error->message ?? 'Paiement échoué');
        }

        return $this->formatResponse(true, 'Échec de paiement traité');
    }

    private function handleChargeDispute($charge): array
    {
        // Gérer les litiges/contestations
        $this->logInfo('Litige créé', ['charge_id' => $charge->id]);
        return $this->formatResponse(true, 'Litige traité');
    }

    public function refundPayment(Payment $payment, float $amount = null): array
    {
        try {
            $refundAmount = $amount ? intval($amount * 100) : null;
            $chargeId = $payment->metadata['stripe_charges'][0]['id'] ?? null;

            if (!$chargeId) {
                return $this->formatResponse(false, 'ID de charge Stripe manquant');
            }

            $refund = \Stripe\Refund::create([
                'charge' => $chargeId,
                'amount' => $refundAmount,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'original_transaction_id' => $payment->transaction_id
                ]
            ]);

            $refundedAmount = $refund->amount / 100;
            $payment->update([
                'status' => $refundedAmount >= $payment->total_amount ? 
                    Payment::STATUS_REFUNDED : Payment::STATUS_PARTIALLY_REFUNDED,
                'refund_amount' => $refundedAmount,
                'refund_date' => now(),
                'metadata' => array_merge($payment->metadata ?? [], [
                    'stripe_refund_id' => $refund->id
                ])
            ]);

            return $this->formatResponse(true, 'Remboursement effectué avec succès', [
                'refund_id' => $refund->id,
                'amount' => $refundedAmount
            ]);

        } catch (Exception $e) {
            $this->logError('Erreur lors du remboursement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return $this->formatResponse(false, 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }

    public function validateConfiguration(): bool
    {
        $required = ['publishable_key', 'secret_key'];
        
        foreach ($required as $field) {
            if (empty($this->config[$field])) {
                return false;
            }
        }

        // Test de connexion avec Stripe
        try {
            Stripe::setApiKey($this->config['secret_key']);
            \Stripe\Account::retrieve();
            return true;
        } catch (Exception $e) {
            $this->logError('Configuration Stripe invalide', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getRequiredConfigFields(): array
    {
        return [
            'publishable_key' => [
                'label' => 'Clé publique Stripe',
                'type' => 'text',
                'required' => true,
                'help' => 'Votre clé publique Stripe (pk_...)'
            ],
            'secret_key' => [
                'label' => 'Clé secrète Stripe',
                'type' => 'password',
                'required' => true,
                'help' => 'Votre clé secrète Stripe (sk_...)'
            ],
            'webhook_secret' => [
                'label' => 'Secret webhook',
                'type' => 'password',
                'required' => false,
                'help' => 'Secret pour vérifier les webhooks Stripe'
            ]
        ];
    }
}
