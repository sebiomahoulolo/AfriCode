<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class PaymentSettingsController extends Controller
{
    /**
     * Afficher les paramètres de paiement
     */
    public function index()
    {
        $settings = [
            'stripe' => [
                'key' => config('services.stripe.key'),
                'secret' => config('services.stripe.secret') ? '***' : '',
                'webhook_secret' => config('services.stripe.webhook_secret') ? '***' : '',
            ],
            'fedapay' => [
                'api_key' => config('services.fadapay.api_key') ? '***' : '',
                'public_key' => config('services.fadapay.public_key') ? '***' : '',
                'secret' => config('services.fadapay.secret') ? '***' : '',
                'environment' => config('services.fadapay.environment'),
                'currency' => config('services.fadapay.currency'),
            ]
        ];

        return view('admin.payment-settings.index', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres FedaPay
     */
    public function updateFedapay(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'public_key' => 'required|string',
            'secret' => 'nullable|string',
            'environment' => 'required|in:live,sandbox',
            'currency' => 'required|in:XOF,USD,EUR',
        ]);

        try {
            $this->updateEnvFile([
                'FADAPAY_API_KEY' => $request->api_key,
                'FADAPAY_PUBLIC_KEY' => $request->public_key,
                'FADAPAY_SECRET' => $request->secret ?? '',
                'FADAPAY_ENVIRONMENT' => $request->environment,
                'FADAPAY_CURRENCY' => $request->currency,
            ]);

            // Effacer le cache de configuration
            Artisan::call('config:clear');

            return redirect()->back()->with('success', 'Paramètres FedaPay mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour les paramètres Stripe
     */
    public function updateStripe(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'secret' => 'required|string',
            'webhook_secret' => 'nullable|string',
        ]);

        try {
            $this->updateEnvFile([
                'STRIPE_PUBLISHABLE_KEY' => $request->key,
                'STRIPE_SECRET_KEY' => $request->secret,
                'STRIPE_WEBHOOK_SECRET' => $request->webhook_secret ?? '',
            ]);

            // Effacer le cache de configuration
            Artisan::call('config:clear');

            return redirect()->back()->with('success', 'Paramètres Stripe mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Tester la connexion FedaPay
     */
    public function testFedapay()
    {
        try {
            $apiKey = config('services.fadapay.api_key');
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clé API FedaPay non configurée'
                ]);
            }

            // Test simple de l'API FedaPay
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get(config('services.fadapay.base_url') . '/v1/account');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connexion FedaPay réussie',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de connexion FedaPay: ' . $response->body()
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mettre à jour le fichier .env
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            throw new \Exception('Fichier .env non trouvé');
        }

        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            $value = addslashes($value);
            
            if (preg_match("/^{$key}=.*$/m", $envContent)) {
                // Mettre à jour la ligne existante
                $envContent = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envContent);
            } else {
                // Ajouter une nouvelle ligne
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);
    }

    /**
     * Obtenir les statistiques de paiement
     */
    public function getPaymentStats()
    {
        $stats = [
            'total_payments' => \App\Models\Payment::count(),
            'completed_payments' => \App\Models\Payment::where('status', 'completed')->count(),
            'pending_payments' => \App\Models\Payment::where('status', 'pending')->count(),
            'failed_payments' => \App\Models\Payment::where('status', 'failed')->count(),
            'stripe_payments' => \App\Models\Payment::where('payment_gateway', 'stripe')->where('status', 'completed')->count(),
            'fedapay_payments' => \App\Models\Payment::where('payment_gateway', 'fedapay')->where('status', 'completed')->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
        ];

        return response()->json($stats);
    }
}
