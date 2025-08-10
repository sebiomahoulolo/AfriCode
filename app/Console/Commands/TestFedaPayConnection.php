<?php

namespace App\Console\Commands;

use App\Services\FedaPayService;
use Illuminate\Console\Command;

class TestFedaPayConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fedapay:test {--show-config : Show current configuration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the FedaPay connection and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test de la connexion FedaPay...');
        $this->newLine();

        if ($this->option('show-config')) {
            $this->showConfiguration();
            $this->newLine();
        }

        $fedaPayService = new FedaPayService();

        // Test de base de la configuration
        $apiKey = config('services.fadapay.api_key');
        $publicKey = config('services.fadapay.public_key');
        $environment = config('services.fadapay.environment');

        if (!$apiKey) {
            $this->error('❌ Clé API FedaPay manquante');
            return 1;
        }

        if (!$publicKey) {
            $this->error('❌ Clé publique FedaPay manquante');
            return 1;
        }

        $this->info("✅ Configuration de base OK");
        $this->info("🌍 Environnement: {$environment}");

        // Test de l'API
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(10)->get(config('services.fadapay.base_url') . '/v1/account');

            if ($response->successful()) {
                $data = $response->json();
                $this->info('✅ Connexion API réussie');
                
                if (isset($data['name'])) {
                    $this->info("📊 Compte: {$data['name']}");
                }
                
                if (isset($data['balance'])) {
                    $this->info("💰 Solde: {$data['balance']}");
                }

                $this->newLine();
                $this->info('🎉 FedaPay est correctement configuré et fonctionnel !');
                return 0;

            } else {
                $this->error('❌ Erreur API: ' . $response->status() . ' - ' . $response->body());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion: ' . $e->getMessage());
            return 1;
        }
    }

    private function showConfiguration()
    {
        $this->info('📋 Configuration actuelle:');
        $this->table(
            ['Paramètre', 'Valeur'],
            [
                ['API Key', config('services.fadapay.api_key') ? '✅ Configuré' : '❌ Manquant'],
                ['Public Key', config('services.fadapay.public_key') ? '✅ Configuré' : '❌ Manquant'],
                ['Secret', config('services.fadapay.secret') ? '✅ Configuré' : '❌ Manquant'],
                ['Environment', config('services.fadapay.environment')],
                ['Currency', config('services.fadapay.currency')],
                ['Base URL', config('services.fadapay.base_url')],
            ]
        );
    }
}
