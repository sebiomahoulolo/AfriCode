<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $mode;
    private $baseUrl;

    public function __construct()
    {
        // Clés PayPal directement dans le code
        $this->clientId = 'AWtOnvKHefbW9y0nzB4-_85OgbjkG3_9EM8wKtp7JYSFESbJ7pV2wenubnx_tAr68qziVEhQ7SELnYWJ';
        $this->clientSecret = 'EDzbZGmG8nCzUoVYn5LgfaMvxMzacINdOpgw4woNUnUIuH-7ZcQlntHovN7K4To8fGMuajqp-a9RWoFO';
        $this->mode = 'sandbox'; // 'sandbox' ou 'live'
        $this->baseUrl = $this->mode === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Obtenir un token d'accès PayPal
     */
    public function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'];
            }

            Log::error('Erreur PayPal - Token d\'accès: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception PayPal - Token d\'accès: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Créer une commande PayPal
     */
    public function createOrder($amount, $currency = 'EUR', $description = 'Paiement de cours')
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ],
                        'description' => $description
                    ]
                ],
                'application_context' => [
                    'return_url' => route('payment.paypal.success'),
                    'cancel_url' => route('payment.paypal.cancel'),
                    'brand_name' => 'AfriCode',
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ]
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => uniqid()
                ])
                ->post($this->baseUrl . '/v2/checkout/orders', $orderData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erreur PayPal - Création commande: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception PayPal - Création commande: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Capturer un paiement PayPal
     */
    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erreur PayPal - Capture: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception PayPal - Capture: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtenir les détails d'une commande PayPal
     */
    public function getOrderDetails($orderId)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->get($this->baseUrl . '/v2/checkout/orders/' . $orderId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erreur PayPal - Détails commande: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception PayPal - Détails commande: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Créer un remboursement PayPal
     */
    public function createRefund($captureId, $amount = null, $currency = 'EUR')
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $refundData = [];
            if ($amount) {
                $refundData['amount'] = [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', '')
                ];
            }

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/v2/payments/captures/' . $captureId . '/refund', $refundData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erreur PayPal - Remboursement: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception PayPal - Remboursement: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifier un webhook PayPal
     */
    public function verifyWebhook($headers, $body)
    {
        $webhookId = config('services.paypal.webhook_id');
        if (!$webhookId) {
            return false;
        }

        try {
            $response = Http::withToken($this->getAccessToken())
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/v1/notifications/verify-webhook-signature', [
                    'auth_algo' => $headers['paypal-auth-algo'] ?? '',
                    'cert_id' => $headers['paypal-cert-id'] ?? '',
                    'transmission_id' => $headers['paypal-transmission-id'] ?? '',
                    'transmission_sig' => $headers['paypal-transmission-sig'] ?? '',
                    'transmission_time' => $headers['paypal-transmission-time'] ?? '',
                    'webhook_id' => $webhookId,
                    'webhook_event' => json_decode($body, true)
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['verification_status'] === 'SUCCESS';
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Exception PayPal - Vérification webhook: ' . $e->getMessage());
            return false;
        }
    }
}
