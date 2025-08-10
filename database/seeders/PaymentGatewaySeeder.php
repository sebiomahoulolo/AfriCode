<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;
use App\Services\Payment\PaymentManager;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentManager = new PaymentManager();
        $paymentManager->installDefaultGateways();
        
        // Activer et configurer Stripe par défaut pour les tests
        $stripe = PaymentGateway::where('name', 'stripe')->first();
        if ($stripe) {
            $stripe->update([
                'is_active' => false, // Désactivé par défaut jusqu'à configuration
                'is_default' => true,
                'test_mode' => true,
                'order_priority' => 1,
                'fees_percentage' => 2.9,
                'fees_fixed' => 30,
                'configuration' => [
                    // Les clés seront ajoutées via l'interface admin
                ]
            ]);
        }
        
        // Configurer Orange Money
        $orangeMoney = PaymentGateway::where('name', 'orange_money')->first();
        if ($orangeMoney) {
            $orangeMoney->update([
                'is_active' => false,
                'test_mode' => true,
                'order_priority' => 2,
                'fees_percentage' => 1.5,
                'fees_fixed' => 0,
                'supported_currencies' => ['XOF']
            ]);
        }
        
        // Configurer PayPal
        $paypal = PaymentGateway::where('name', 'paypal')->first();
        if ($paypal) {
            $paypal->update([
                'is_active' => false,
                'test_mode' => true,
                'order_priority' => 3,
                'fees_percentage' => 3.4,
                'fees_fixed' => 35,
                'supported_currencies' => ['USD', 'EUR']
            ]);
        }
        
        $this->command->info('Passerelles de paiement installées avec succès!');
    }
}
