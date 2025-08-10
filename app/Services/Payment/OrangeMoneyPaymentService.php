<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Services\Payment\BasePaymentService;
use Exception;

class OrangeMoneyPaymentService extends BasePaymentService
{
    private string $apiUrl;
    private string $merchantKey;
    private string $merchantSecret;

    public function __construct($gateway)
    {
        parent::__construct($gateway);
        $this->apiUrl = $this->config['api_url'] ?? 'https://api.orange.com/orange-money-webpay/dev/v1';
        $this->merchantKey = $this->config['merchant_key'] ?? '';
        $this->merchantSecret = $this->config['merchant_secret'] ?? '';
    }

    public function initiatePayment(Payment $payment): array
    {
        try {
            $requestData = [
                'merchant_key' => $this->merchantKey,
                'currency' => $payment->currency,
                'order_id' => $payment->transaction_id,
                'amount' => intval($payment->total_amount),
                'return_url' => $this->getReturnUrl($payment, 'success'),
                'cancel_url' => $this->getReturnUrl($payment, 'failed'),
                'notif_url' => $this->getWebhookUrl(),
                'lang' => 'fr',
                'reference' => "COURSE_{$payment->course_id}_{$payment->user_id}"
            ];

            $response = $this->makeApiCall('/webpayment', $requestData);

            if (isset($response['pay_token']) && isset($response['payment_url'])) {
                $payment->update([
                    'external_transaction_id' => $response['pay_token'],
                    'status' => Payment::STATUS_PROCESSING,
                    'metadata' => [
                        'orange_money_token' => $response['pay_token'],
                        'payment_url' => $response['payment_url']
                    ]
                ]);

                return $this->formatResponse(true, 'Paiement Orange Money initié', [
                    'payment_url' => $response['payment_url'],
                    'pay_token' => $response['pay_token']
                ]);
            }

            return $this->formatResponse(false, 'Réponse invalide d\'Orange Money');

        } catch (Exception $e) {
            $this->logError('Erreur Orange Money', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            $payment->markAsFailed($e->getMessage());
            return $this->formatResponse(false, 'Erreur Orange Money: ' . $e->getMessage());
        }
    }

    public function checkPaymentStatus(Payment $payment): array
    {
        try {
            $payToken = $payment->metadata['orange_money_token'] ?? null;
            
            if (!$payToken) {
                return $this->formatResponse(false, 'Token Orange Money manquant');
            }

            $response = $this->makeApiCall('/webpayment/' . $payToken, [], 'GET');

            switch ($response['status'] ?? '') {
                case 'SUCCESS':
                    $payment->markAsCompleted($payToken, [
                        'orange_txn_id' => $response['txnid'] ?? '',
                        'orange_status' => $response['status']
                    ]);
                    return $this->formatResponse(true, 'Paiement Orange Money confirmé');

                case 'PENDING':
                    return $this->formatResponse(true, 'Paiement en attente');

                case 'FAILED':
                case 'EXPIRED':
                    $payment->markAsFailed($response['message'] ?? 'Paiement échoué');
                    return $this->formatResponse(false, 'Paiement Orange Money échoué');

                default:
                    return $this->formatResponse(false, 'Statut inconnu: ' . ($response['status'] ?? 'UNKNOWN'));
            }

        } catch (Exception $e) {
            $this->logError('Erreur vérification statut Orange Money', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return $this->formatResponse(false, 'Erreur vérification: ' . $e->getMessage());
        }
    }

    public function handleWebhook(array $payload): array
    {
        try {
            $orderId = $payload['order_id'] ?? '';
            $status = $payload['status'] ?? '';
            $txnId = $payload['txnid'] ?? '';

            $payment = Payment::where('transaction_id', $orderId)->first();

            if (!$payment) {
                return $this->formatResponse(false, 'Paiement non trouvé');
            }

            switch ($status) {
                case 'SUCCESS':
                    $payment->markAsCompleted($txnId, [
                        'orange_txn_id' => $txnId,
                        'webhook_payload' => $payload
                    ]);

                    // Activer l'inscription
                    if ($payment->course && $payment->user) {
                        $payment->user->courses()->syncWithoutDetaching([
                            $payment->course->id => [
                                'enrolled_at' => now(),
                                'progress_percentage' => 0
                            ]
                        ]);
                    }
                    break;

                case 'FAILED':
                case 'EXPIRED':
                    $payment->markAsFailed($payload['message'] ?? 'Paiement échoué');
                    break;
            }

            return $this->formatResponse(true, 'Webhook Orange Money traité');

        } catch (Exception $e) {
            $this->logError('Erreur webhook Orange Money', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return $this->formatResponse(false, 'Erreur webhook: ' . $e->getMessage());
        }
    }

    public function refundPayment(Payment $payment, float $amount = null): array
    {
        // Orange Money ne supporte généralement pas les remboursements automatiques
        return $this->formatResponse(false, 'Les remboursements Orange Money doivent être traités manuellement');
    }

    public function validateConfiguration(): bool
    {
        $required = ['merchant_key', 'merchant_secret', 'api_url'];
        
        foreach ($required as $field) {
            if (empty($this->config[$field])) {
                return false;
            }
        }

        // Test de connexion
        try {
            $testData = [
                'merchant_key' => $this->merchantKey,
                'currency' => 'XOF',
                'order_id' => 'TEST_' . time(),
                'amount' => 100
            ];

            $response = $this->makeApiCall('/webpayment', $testData);
            return isset($response['pay_token']) || isset($response['error_code']);

        } catch (Exception $e) {
            $this->logError('Test de configuration Orange Money échoué', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getRequiredConfigFields(): array
    {
        return [
            'merchant_key' => [
                'label' => 'Clé marchand',
                'type' => 'text',
                'required' => true,
                'help' => 'Votre identifiant marchand Orange Money'
            ],
            'merchant_secret' => [
                'label' => 'Secret marchand',
                'type' => 'password',
                'required' => true,
                'help' => 'Votre clé secrète marchand Orange Money'
            ],
            'api_url' => [
                'label' => 'URL API',
                'type' => 'url',
                'required' => true,
                'help' => 'URL de l\'API Orange Money (dev ou prod)',
                'default' => 'https://api.orange.com/orange-money-webpay/dev/v1'
            ]
        ];
    }

    private function makeApiCall(string $endpoint, array $data = [], string $method = 'POST'): array
    {
        $url = rtrim($this->apiUrl, '/') . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->generateToken()
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => !$this->isTestMode()
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Erreur cURL: $error");
        }

        if ($httpCode >= 400) {
            throw new Exception("Erreur HTTP $httpCode: $response");
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Réponse JSON invalide: $response");
        }

        return $decoded;
    }

    private function generateToken(): string
    {
        // Génération du token d'authentification pour Orange Money
        // Cette implémentation dépend de la documentation Orange Money
        $timestamp = time();
        $signature = hash_hmac('sha256', $this->merchantKey . $timestamp, $this->merchantSecret);
        
        return base64_encode($this->merchantKey . ':' . $timestamp . ':' . $signature);
    }
}
