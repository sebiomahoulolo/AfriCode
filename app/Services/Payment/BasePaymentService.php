<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Course;
use App\Models\User;
use Exception;

abstract class BasePaymentService
{
    protected PaymentGateway $gateway;
    protected array $config;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->config = $gateway->configuration ?? [];
    }

    /**
     * Initier un paiement
     */
    abstract public function initiatePayment(Payment $payment): array;

    /**
     * Vérifier le statut d'un paiement
     */
    abstract public function checkPaymentStatus(Payment $payment): array;

    /**
     * Traiter un webhook
     */
    abstract public function handleWebhook(array $payload): array;

    /**
     * Effectuer un remboursement
     */
    abstract public function refundPayment(Payment $payment, float $amount = null): array;

    /**
     * Valider la configuration de la passerelle
     */
    abstract public function validateConfiguration(): bool;

    /**
     * Obtenir les champs de configuration requis
     */
    abstract public function getRequiredConfigFields(): array;

    /**
     * Créer un objet Payment
     */
    protected function createPayment(User $user, Course $course, float $amount, string $currency = 'XOF'): Payment
    {
        $fees = $this->gateway->calculateFees($amount);
        $totalAmount = $amount + $fees;

        return Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_gateway_id' => $this->gateway->id,
            'amount' => $amount,
            'fees' => $fees,
            'total_amount' => $totalAmount,
            'currency' => $currency,
            'payment_gateway' => $this->gateway->name,
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => $this->generateTransactionId(),
        ]);
    }

    /**
     * Générer un ID de transaction unique
     */
    protected function generateTransactionId(): string
    {
        return strtoupper($this->gateway->name) . '_' . time() . '_' . rand(1000, 9999);
    }

    /**
     * Logger une erreur
     */
    protected function logError(string $message, array $context = []): void
    {
        \Log::error("[{$this->gateway->name}] $message", $context);
    }

    /**
     * Logger une info
     */
    protected function logInfo(string $message, array $context = []): void
    {
        \Log::info("[{$this->gateway->name}] $message", $context);
    }

    /**
     * Valider le montant pour cette passerelle
     */
    public function validateAmount(float $amount): bool
    {
        return $this->gateway->isAvailableForAmount($amount);
    }

    /**
     * Valider la devise pour cette passerelle
     */
    public function validateCurrency(string $currency): bool
    {
        return $this->gateway->isAvailableForCurrency($currency);
    }

    /**
     * Obtenir la configuration sécurisée (sans les secrets)
     */
    public function getPublicConfig(): array
    {
        $publicFields = ['environment', 'currency', 'country'];
        return array_intersect_key($this->config, array_flip($publicFields));
    }

    /**
     * Formater une réponse standard
     */
    protected function formatResponse(bool $success, string $message, array $data = []): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'gateway' => $this->gateway->name,
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Vérifier si la passerelle est en mode test
     */
    protected function isTestMode(): bool
    {
        return $this->gateway->test_mode;
    }

    /**
     * Obtenir l'URL de retour après paiement
     */
    protected function getReturnUrl(Payment $payment, string $status = 'success'): string
    {
        return route("payment.{$status}", ['payment' => $payment->id]);
    }

    /**
     * Obtenir l'URL de notification webhook
     */
    protected function getWebhookUrl(): string
    {
        return route('webhook.' . $this->gateway->name);
    }
}
