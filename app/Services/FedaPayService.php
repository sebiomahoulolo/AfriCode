<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FedaPayService
{
    private $apiKey;
    private $publicKey;
    private $baseUrl;
    private $environment;
    private $currency;

    public function __construct()
    {
        $this->apiKey = config('services.fedapay.api_key');
        $this->publicKey = config('services.fedapay.public_key');
        $this->baseUrl = config('services.fedapay.base_url');
        $this->environment = config('services.fedapay.environment');
        $this->currency = config('services.fedapay.currency');
        
        Log::info('FedaPayService initialized', [
            'api_key_set' => !empty($this->apiKey),
            'environment' => $this->environment,
            'base_url' => $this->baseUrl
        ]);
    }

    /**
     * Créer une transaction FedaPay
     */
    public function createTransaction($payment, $user, $course)
    {
        Log::info('FedaPayService createTransaction called', [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency
        ]);

        try {
            // Convertir le montant en XOF si nécessaire
            $convertedAmount = $this->convertCurrency($payment->amount, $payment->currency, 'XOF');
            
            Log::info('Currency conversion', [
                'original_amount' => $payment->amount,
                'original_currency' => $payment->currency,
                'converted_amount' => $convertedAmount,
                'converted_currency' => 'XOF'
            ]);

            // Mettre à jour le paiement avec les montants convertis
            $payment->update([
                'converted_amount' => $convertedAmount,
                'converted_currency' => 'XOF'
            ]);

            $payload = [
                'description' => "Achat du cours: {$course->title}",
                'amount' => $convertedAmount,
                'currency' => [
                    'iso' => 'XOF'
                ],
                'callback_url' => config('services.fedapay.webhook_url'),
                'customer' => [
                    'firstname' => $user->first_name ?? explode(' ', $user->name)[0] ?? 'Client',
                    'lastname' => $user->last_name ?? explode(' ', $user->name)[1] ?? '',
                    'email' => $user->email,
                    'phone_number' => $user->phone ?? null
                ]
            ];

            Log::info('Sending request to FedaPay API', [
                'payload' => $payload,
                'api_url' => $this->baseUrl . '/transactions'
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transactions', $payload);

            Log::info('FedaPay API response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Retourner les données de transaction
                return [
                    'id' => $data['id'] ?? null,
                    'reference' => $data['reference'] ?? $payment->transaction_id,
                    'token' => $data['token'] ?? null,
                    'url' => $data['url'] ?? null,
                    'status' => $data['status'] ?? 'pending'
                ];
            } else {
                Log::error('FedaPay API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

        } catch (Exception $e) {
            Log::error('FedaPay transaction creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function checkTransactionStatus($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/v1/transactions/{$transactionId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('FedaPay Status Check Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convertir les devises
     */
    private function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Taux de change approximatifs (vous devriez utiliser une API de change en temps réel)
        $exchangeRates = [
            'EUR' => [
                'XOF' => 655.957, // 1 EUR = 655.957 XOF
                'USD' => 1.10,     // 1 EUR = 1.10 USD
            ],
            'USD' => [
                'XOF' => 600,      // 1 USD = 600 XOF (approximatif)
                'EUR' => 0.91,     // 1 USD = 0.91 EUR
            ],
            'XOF' => [
                'EUR' => 0.00152,  // 1 XOF = 0.00152 EUR
                'USD' => 0.00167,  // 1 XOF = 0.00167 USD
            ]
        ];

        if (isset($exchangeRates[$fromCurrency][$toCurrency])) {
            return round($amount * $exchangeRates[$fromCurrency][$toCurrency]);
        }

        // Si pas de taux trouvé, retourner le montant original
        Log::warning("Taux de change non trouvé pour {$fromCurrency} vers {$toCurrency}");
        return $amount;
    }

    /**
     * Valider la signature du webhook
     */
    public function validateWebhookSignature($payload, $signature, $secret = null)
    {
        $secret = $secret ?? config('services.fadapay.secret');
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Traiter un webhook
     */
    public function processWebhook($data)
    {
        try {
            $transactionId = $data['id'] ?? null;
            $status = $data['status'] ?? null;

            if (!$transactionId) {
                Log::error('FedaPay webhook sans transaction ID');
                return false;
            }

            $payment = Payment::where('gateway_transaction_id', $transactionId)
                ->orWhere('transaction_id', $transactionId)
                ->first();

            if (!$payment) {
                Log::error("Paiement non trouvé pour la transaction: {$transactionId}");
                return false;
            }

            // Mettre à jour le statut du paiement selon le statut FedaPay
            switch ($status) {
                case 'approved':
                case 'completed':
                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                        'gateway_response' => array_merge($payment->gateway_response ?? [], $data)
                    ]);

                    // Activer l'inscription
                    $enrollment = $payment->enrollment;
                    if ($enrollment && !$enrollment->enrolled_at) {
                        $enrollment->update(['enrolled_at' => now()]);
                    }
                    
                    Log::info("Paiement FedaPay confirmé: {$transactionId}");
                    break;

                case 'declined':
                case 'failed':
                case 'canceled':
                    $payment->update([
                        'status' => 'failed',
                        'gateway_response' => array_merge($payment->gateway_response ?? [], $data)
                    ]);
                    
                    Log::info("Paiement FedaPay échoué: {$transactionId}");
                    break;

                default:
                    Log::info("Statut FedaPay non traité: {$status} pour {$transactionId}");
                    break;
            }

            return true;

        } catch (Exception $e) {
            Log::error('Erreur traitement webhook FedaPay: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir la configuration pour Checkout.js
     */
    public function getCheckoutConfig($payment, $course, $user)
    {
        return [
            'public_key' => $this->publicKey,
            'environment' => $this->environment,
            'transaction' => [
                'id' => $payment->gateway_transaction_id,
                'amount' => $payment->converted_amount ?? $payment->amount,
                'description' => "Achat du cours: {$course->title}",
                'custom_metadata' => [
                    'enrollment_id' => $payment->enrollment_id,
                    'course_id' => $course->id,
                    'payment_id' => $payment->id
                ]
            ],
            'customer' => [
                'email' => $user->email,
                'firstname' => $user->first_name ?? $user->name,
                'lastname' => $user->last_name ?? '',
            ],
            'currency' => [
                'iso' => $payment->converted_currency ?? $this->currency
            ],
            'callback_url' => route('payment.fedapay.webhook'),
            'return_url' => route('payment.success', ['payment' => $payment->id])
        ];
    }
}
