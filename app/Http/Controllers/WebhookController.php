<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    private PaymentManager $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Gérer les webhooks Stripe
     */
    public function stripe(Request $request)
    {
        try {
            $payload = $request->all();
            
            Log::info('Webhook Stripe reçu', ['payload' => $payload]);
            
            $result = $this->paymentManager->handleWebhook('stripe', $payload);
            
            if ($result['success']) {
                return response()->json(['status' => 'success'], 200);
            } else {
                Log::error('Erreur traitement webhook Stripe', ['result' => $result]);
                return response()->json(['status' => 'error', 'message' => $result['message']], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception webhook Stripe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Gérer les webhooks Orange Money
     */
    public function orangeMoney(Request $request)
    {
        try {
            $payload = $request->all();
            
            Log::info('Webhook Orange Money reçu', ['payload' => $payload]);
            
            $result = $this->paymentManager->handleWebhook('orange_money', $payload);
            
            if ($result['success']) {
                return response()->json(['status' => 'success'], 200);
            } else {
                Log::error('Erreur traitement webhook Orange Money', ['result' => $result]);
                return response()->json(['status' => 'error', 'message' => $result['message']], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception webhook Orange Money', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Gérer les webhooks PayPal
     */
    public function paypal(Request $request)
    {
        try {
            $payload = $request->all();
            
            Log::info('Webhook PayPal reçu', ['payload' => $payload]);
            
            $result = $this->paymentManager->handleWebhook('paypal', $payload);
            
            if ($result['success']) {
                return response()->json(['status' => 'success'], 200);
            } else {
                Log::error('Erreur traitement webhook PayPal', ['result' => $result]);
                return response()->json(['status' => 'error', 'message' => $result['message']], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception webhook PayPal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Webhook générique pour d'autres passerelles
     */
    public function handleGeneric(Request $request, string $gateway)
    {
        try {
            $payload = $request->all();
            
            Log::info("Webhook {$gateway} reçu", ['payload' => $payload]);
            
            $result = $this->paymentManager->handleWebhook($gateway, $payload);
            
            if ($result['success']) {
                return response()->json(['status' => 'success'], 200);
            } else {
                Log::error("Erreur traitement webhook {$gateway}", ['result' => $result]);
                return response()->json(['status' => 'error', 'message' => $result['message']], 400);
            }
            
        } catch (\Exception $e) {
            Log::error("Exception webhook {$gateway}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }
}
