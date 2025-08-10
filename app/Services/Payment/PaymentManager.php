<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\Payment;
use App\Models\Course;
use App\Models\User;
use App\Services\Payment\StripePaymentService;
use App\Services\Payment\OrangeMoneyPaymentService;
use Exception;

class PaymentManager
{
    private array $services = [];

    public function __construct()
    {
        $this->registerServices();
    }

    /**
     * Enregistrer tous les services de paiement disponibles
     */
    private function registerServices(): void
    {
        $this->services = [
            'stripe' => StripePaymentService::class,
            'orange_money' => OrangeMoneyPaymentService::class,
            // Ajoutez d'autres services ici
        ];
    }

    /**
     * Obtenir un service de paiement par nom
     */
    public function getService(string $gatewayName): BasePaymentService
    {
        if (!isset($this->services[$gatewayName])) {
            throw new Exception("Service de paiement non supporté: $gatewayName");
        }

        $gateway = PaymentGateway::where('name', $gatewayName)
            ->where('is_active', true)
            ->firstOrFail();

        $serviceClass = $this->services[$gatewayName];
        return new $serviceClass($gateway);
    }

    /**
     * Obtenir toutes les passerelles actives
     */
    public function getActiveGateways(): \Illuminate\Database\Eloquent\Collection
    {
        return PaymentGateway::active()
            ->orderBy('order_priority')
            ->get();
    }

    /**
     * Obtenir les passerelles disponibles pour un montant et une devise
     */
    public function getAvailableGateways(float $amount, string $currency = 'XOF'): \Illuminate\Database\Eloquent\Collection
    {
        return PaymentGateway::active()
            ->forCurrency($currency)
            ->forAmount($amount)
            ->orderBy('order_priority')
            ->get();
    }

    /**
     * Obtenir la passerelle par défaut
     */
    public function getDefaultGateway(): ?PaymentGateway
    {
        return PaymentGateway::active()->default()->first();
    }

    /**
     * Créer un paiement avec la passerelle spécifiée
     */
    public function createPayment(User $user, Course $course, string $gatewayName, float $amount = null, string $currency = 'XOF'): Payment
    {
        $gateway = PaymentGateway::where('name', $gatewayName)
            ->where('is_active', true)
            ->firstOrFail();

        $amount = $amount ?? $course->price ?? 0;

        // Vérifier que la passerelle supporte cette devise et ce montant
        if (!$gateway->isAvailableForCurrency($currency)) {
            throw new Exception("Cette passerelle ne supporte pas la devise $currency");
        }

        if (!$gateway->isAvailableForAmount($amount)) {
            throw new Exception("Le montant $amount n'est pas valide pour cette passerelle");
        }

        $fees = $gateway->calculateFees($amount);
        $totalAmount = $amount + $fees;

        return Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_gateway_id' => $gateway->id,
            'amount' => $amount,
            'fees' => $fees,
            'total_amount' => $totalAmount,
            'currency' => $currency,
            'payment_gateway' => $gateway->name,
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => $this->generateTransactionId($gateway->name),
        ]);
    }

    /**
     * Initier un paiement
     */
    public function initiatePayment(Payment $payment): array
    {
        $service = $this->getService($payment->payment_gateway);
        return $service->initiatePayment($payment);
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus(Payment $payment): array
    {
        $service = $this->getService($payment->payment_gateway);
        return $service->checkPaymentStatus($payment);
    }

    /**
     * Traiter un webhook
     */
    public function handleWebhook(string $gatewayName, array $payload): array
    {
        $service = $this->getService($gatewayName);
        return $service->handleWebhook($payload);
    }

    /**
     * Effectuer un remboursement
     */
    public function refundPayment(Payment $payment, float $amount = null): array
    {
        if (!$payment->canBeRefunded()) {
            throw new Exception("Ce paiement ne peut pas être remboursé");
        }

        $service = $this->getService($payment->payment_gateway);
        return $service->refundPayment($payment, $amount);
    }

    /**
     * Valider la configuration d'une passerelle
     */
    public function validateGatewayConfiguration(PaymentGateway $gateway): bool
    {
        if (!isset($this->services[$gateway->name])) {
            return false;
        }

        $serviceClass = $this->services[$gateway->name];
        $service = new $serviceClass($gateway);
        
        return $service->validateConfiguration();
    }

    /**
     * Obtenir les champs de configuration requis pour une passerelle
     */
    public function getGatewayConfigFields(string $gatewayName): array
    {
        if (!isset($this->services[$gatewayName])) {
            return [];
        }

        $gateway = new PaymentGateway(['name' => $gatewayName]);
        $serviceClass = $this->services[$gatewayName];
        $service = new $serviceClass($gateway);
        
        return $service->getRequiredConfigFields();
    }

    /**
     * Installer les passerelles par défaut
     */
    public function installDefaultGateways(): void
    {
        $defaultGateways = PaymentGateway::getAvailableGateways();

        foreach ($defaultGateways as $name => $config) {
            PaymentGateway::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => \Str::slug($name),
                    'display_name' => $config['name'],
                    'description' => $config['description'],
                    'icon' => $config['icon'],
                    'supported_currencies' => $config['currencies'],
                    'is_active' => false, // Désactivé par défaut
                    'test_mode' => true,
                    'order_priority' => 0
                ]
            );
        }
    }

    /**
     * Calculer les statistiques de paiement
     */
    public function getPaymentStats(string $period = '30days'): array
    {
        $startDate = match($period) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            '1year' => now()->subYear(),
            default => now()->subDays(30)
        };

        $payments = Payment::where('created_at', '>=', $startDate);

        return [
            'total_payments' => $payments->count(),
            'successful_payments' => $payments->where('status', Payment::STATUS_COMPLETED)->count(),
            'failed_payments' => $payments->whereIn('status', [Payment::STATUS_FAILED, Payment::STATUS_CANCELLED])->count(),
            'pending_payments' => $payments->where('status', Payment::STATUS_PENDING)->count(),
            'total_amount' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'total_fees' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('fees'),
            'by_gateway' => $payments->groupBy('payment_gateway')
                ->map(function ($gatewayPayments) {
                    return [
                        'count' => $gatewayPayments->count(),
                        'amount' => $gatewayPayments->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
                        'success_rate' => $gatewayPayments->where('status', Payment::STATUS_COMPLETED)->count() / max($gatewayPayments->count(), 1) * 100
                    ];
                })
        ];
    }

    /**
     * Générer un ID de transaction unique
     */
    private function generateTransactionId(string $gatewayName): string
    {
        return strtoupper($gatewayName) . '_' . time() . '_' . rand(1000, 9999);
    }

    /**
     * Obtenir les services disponibles
     */
    public function getAvailableServices(): array
    {
        return array_keys($this->services);
    }

    /**
     * Vérifier si un service est disponible
     */
    public function hasService(string $gatewayName): bool
    {
        return isset($this->services[$gatewayName]);
    }
}
