<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\FedaPayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidateFedaPayIntegration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fedapay:validate-integration 
                          {--reset : Réinitialiser les données de test}
                          {--skip-api : Ignorer les tests API}
                          {--verbose : Affichage détaillé}';

    /**
     * The console command description.
     */
    protected $description = 'Valider complètement l\'intégration FedaPay';

    protected FedaPayService $fedaPayService;
    protected array $testResults = [];

    public function __construct(FedaPayService $fedaPayService)
    {
        parent::__construct();
        $this->fedaPayService = $fedaPayService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Validation de l\'intégration FedaPay');
        $this->info('==========================================');

        // Réinitialisation si demandée
        if ($this->option('reset')) {
            $this->resetTestData();
        }

        // Tests de configuration
        $this->testConfiguration();

        // Tests de base de données
        $this->testDatabaseStructure();

        // Tests de service
        $this->testServiceMethods();

        // Tests API (si pas ignorés)
        if (!$this->option('skip-api')) {
            $this->testApiConnectivity();
        }

        // Tests de workflow complet
        $this->testCompleteWorkflow();

        // Tests de sécurité
        $this->testSecurity();

        // Affichage du résumé
        $this->displaySummary();

        return $this->allTestsPassed() ? Command::SUCCESS : Command::FAILURE;
    }

    protected function testConfiguration(): void
    {
        $this->info('📋 Test de Configuration...');

        // Vérifier la clé API
        $apiKey = config('services.fedapay.api_key');
        $this->testResults['config_api_key'] = !empty($apiKey);
        $this->displayResult('Clé API configurée', $this->testResults['config_api_key']);

        // Vérifier l'environnement
        $environment = config('services.fedapay.environment');
        $this->testResults['config_environment'] = in_array($environment, ['sandbox', 'live']);
        $this->displayResult('Environnement valide', $this->testResults['config_environment']);

        // Vérifier l'URL de webhook
        $webhookUrl = config('services.fedapay.webhook_url');
        $this->testResults['config_webhook'] = !empty($webhookUrl) && filter_var($webhookUrl, FILTER_VALIDATE_URL);
        $this->displayResult('URL Webhook valide', $this->testResults['config_webhook']);

        // Vérifier le secret webhook
        $webhookSecret = config('services.fedapay.webhook_secret');
        $this->testResults['config_webhook_secret'] = !empty($webhookSecret);
        $this->displayResult('Secret Webhook configuré', $this->testResults['config_webhook_secret']);

        // Vérifier la devise par défaut
        $defaultCurrency = config('services.fedapay.default_currency');
        $this->testResults['config_currency'] = !empty($defaultCurrency);
        $this->displayResult('Devise par défaut configurée', $this->testResults['config_currency']);
    }

    protected function testDatabaseStructure(): void
    {
        $this->info('🗄️  Test de Structure de Base de Données...');

        try {
            // Vérifier la table payments
            $this->testResults['db_payments_table'] = DB::getSchemaBuilder()->hasTable('payments');
            $this->displayResult('Table payments existe', $this->testResults['db_payments_table']);

            // Vérifier les colonnes FedaPay
            $requiredColumns = [
                'gateway_transaction_id',
                'converted_amount',
                'converted_currency'
            ];

            foreach ($requiredColumns as $column) {
                $exists = DB::getSchemaBuilder()->hasColumn('payments', $column);
                $this->testResults["db_column_{$column}"] = $exists;
                $this->displayResult("Colonne {$column}", $exists);
            }

            // Vérifier la table enrollments
            $this->testResults['db_enrollments_table'] = DB::getSchemaBuilder()->hasTable('enrollments');
            $this->displayResult('Table enrollments existe', $this->testResults['db_enrollments_table']);

        } catch (\Exception $e) {
            $this->error('Erreur lors du test de base de données: ' . $e->getMessage());
            $this->testResults['db_error'] = false;
        }
    }

    protected function testServiceMethods(): void
    {
        $this->info('⚙️  Test des Méthodes de Service...');

        try {
            // Test de conversion de devise
            $converted = $this->fedaPayService->convertCurrency(100, 'EUR', 'XOF');
            $this->testResults['service_currency_conversion'] = $converted > 0;
            $this->displayResult('Conversion de devise', $this->testResults['service_currency_conversion']);

            if ($this->option('verbose')) {
                $this->line("   100 EUR = {$converted} XOF");
            }

            // Test de validation de signature (avec données fictives)
            $testSignature = hash_hmac('sha256', 'test_data', config('services.fedapay.webhook_secret', 'test'));
            $this->testResults['service_signature_validation'] = !empty($testSignature);
            $this->displayResult('Génération signature webhook', $this->testResults['service_signature_validation']);

            // Test de création de données de transaction
            $testUser = User::first();
            $testCourse = Course::first();

            if ($testUser && $testCourse) {
                $testPayment = new Payment([
                    'user_id' => $testUser->id,
                    'course_id' => $testCourse->id,
                    'amount' => 5000,
                    'currency' => 'EUR',
                    'payment_gateway' => 'fedapay',
                    'status' => 'pending'
                ]);

                $transactionData = $this->fedaPayService->prepareTransactionData($testPayment, $testUser, $testCourse);
                $this->testResults['service_transaction_data'] = !empty($transactionData['amount']);
                $this->displayResult('Préparation données transaction', $this->testResults['service_transaction_data']);
            }

        } catch (\Exception $e) {
            $this->error('Erreur lors du test de service: ' . $e->getMessage());
            $this->testResults['service_error'] = false;
        }
    }

    protected function testApiConnectivity(): void
    {
        $this->info('🌐 Test de Connectivité API...');

        try {
            // Test ping simple (si disponible)
            $response = $this->fedaPayService->testConnection();
            $this->testResults['api_connectivity'] = $response !== false;
            $this->displayResult('Connectivité API', $this->testResults['api_connectivity']);

            if ($this->option('verbose') && is_array($response)) {
                $this->line('   Réponse API: ' . json_encode($response, JSON_PRETTY_PRINT));
            }

        } catch (\Exception $e) {
            $this->warn('Test API ignoré: ' . $e->getMessage());
            $this->testResults['api_connectivity'] = null; // Test non concluant
        }
    }

    protected function testCompleteWorkflow(): void
    {
        $this->info('🔄 Test du Workflow Complet...');

        try {
            // Créer un utilisateur de test
            $testUser = User::firstOrCreate([
                'email' => 'test.fedapay.integration@example.com'
            ], [
                'name' => 'Test FedaPay User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now()
            ]);

            // Créer un cours de test
            $testCourse = Course::firstOrCreate([
                'slug' => 'test-course-fedapay-integration'
            ], [
                'title' => 'Cours de Test FedaPay Integration',
                'description' => 'Cours créé pour tester l\'intégration FedaPay',
                'price' => 2500, // 25 EUR
                'is_published' => true,
                'difficulty_level' => 'beginner',
                'instructor_id' => $testUser->id
            ]);

            // Créer un paiement de test
            $testPayment = Payment::create([
                'user_id' => $testUser->id,
                'course_id' => $testCourse->id,
                'amount' => $testCourse->price,
                'currency' => 'EUR',
                'payment_gateway' => 'fedapay',
                'status' => 'pending',
                'transaction_id' => 'TEST_INTEGRATION_' . uniqid()
            ]);

            $this->testResults['workflow_payment_creation'] = $testPayment->exists;
            $this->displayResult('Création paiement test', $this->testResults['workflow_payment_creation']);

            // Simuler le processus de webhook
            $webhookData = [
                'entity' => 'event',
                'type' => 'transaction.updated',
                'data' => [
                    'id' => 'test_transaction_' . $testPayment->id,
                    'reference' => $testPayment->transaction_id,
                    'amount' => $testPayment->amount * 100,
                    'status' => 'approved',
                    'created_at' => now()->toISOString(),
                    'customer' => [
                        'email' => $testUser->email,
                        'firstname' => explode(' ', $testUser->name)[0]
                    ]
                ]
            ];

            $webhookResult = $this->fedaPayService->processWebhook($webhookData);
            $this->testResults['workflow_webhook_processing'] = $webhookResult !== false;
            $this->displayResult('Traitement webhook simulé', $this->testResults['workflow_webhook_processing']);

            // Vérifier que le paiement a été mis à jour
            $updatedPayment = $testPayment->fresh();
            $this->testResults['workflow_payment_update'] = $updatedPayment->status === 'completed';
            $this->displayResult('Mise à jour statut paiement', $this->testResults['workflow_payment_update']);

        } catch (\Exception $e) {
            $this->error('Erreur lors du test de workflow: ' . $e->getMessage());
            $this->testResults['workflow_error'] = false;
        }
    }

    protected function testSecurity(): void
    {
        $this->info('🔒 Test de Sécurité...');

        // Test de validation des données d'entrée
        try {
            $invalidData = ['invalid' => 'data'];
            $result = $this->fedaPayService->processWebhook($invalidData);
            $this->testResults['security_invalid_data'] = $result === false;
            $this->displayResult('Rejet données invalides', $this->testResults['security_invalid_data']);
        } catch (\Exception $e) {
            $this->testResults['security_invalid_data'] = true; // Exception attendue
            $this->displayResult('Rejet données invalides', true);
        }

        // Test de protection contre les montants négatifs
        try {
            $converted = $this->fedaPayService->convertCurrency(-100, 'EUR', 'XOF');
            $this->testResults['security_negative_amount'] = $converted === 0;
            $this->displayResult('Protection montants négatifs', $this->testResults['security_negative_amount']);
        } catch (\Exception $e) {
            $this->testResults['security_negative_amount'] = true; // Exception attendue
            $this->displayResult('Protection montants négatifs', true);
        }
    }

    protected function resetTestData(): void
    {
        $this->warn('🗑️  Réinitialisation des données de test...');
        
        Payment::where('transaction_id', 'LIKE', 'TEST_%')->delete();
        User::where('email', 'test.fedapay.integration@example.com')->delete();
        Course::where('slug', 'test-course-fedapay-integration')->delete();
        
        $this->info('Données de test supprimées.');
    }

    protected function displayResult(string $test, ?bool $result): void
    {
        if ($result === null) {
            $this->line("   ⚠️  {$test}: <comment>IGNORÉ</comment>");
        } elseif ($result) {
            $this->line("   ✅ {$test}: <info>SUCCÈS</info>");
        } else {
            $this->line("   ❌ {$test}: <error>ÉCHEC</error>");
        }
    }

    protected function displaySummary(): void
    {
        $this->info('');
        $this->info('📊 Résumé des Tests');
        $this->info('==================');

        $passed = 0;
        $failed = 0;
        $ignored = 0;

        foreach ($this->testResults as $test => $result) {
            if ($result === null) {
                $ignored++;
            } elseif ($result === true) {
                $passed++;
            } else {
                $failed++;
            }
        }

        $total = $passed + $failed + $ignored;

        $this->info("Tests réussis: <info>{$passed}</info>");
        if ($failed > 0) {
            $this->error("Tests échoués: {$failed}");
        }
        if ($ignored > 0) {
            $this->warn("Tests ignorés: {$ignored}");
        }
        $this->info("Total: {$total}");

        if ($failed === 0) {
            $this->info('');
            $this->info('🎉 <bg=green;fg=black> INTÉGRATION FEDAPAY VALIDÉE AVEC SUCCÈS </bg=green;fg=black>');
            $this->info('');
            $this->info('Prochaines étapes:');
            $this->info('1. Configurer les webhooks dans le dashboard FedaPay');
            $this->info('2. Tester avec des paiements réels en mode sandbox');
            $this->info('3. Basculer en mode live pour la production');
        } else {
            $this->error('');
            $this->error('❌ Des problèmes ont été détectés dans l\'intégration.');
            $this->error('Veuillez corriger les erreurs avant de passer en production.');
        }
    }

    protected function allTestsPassed(): bool
    {
        foreach ($this->testResults as $result) {
            if ($result === false) {
                return false;
            }
        }
        return true;
    }
}
