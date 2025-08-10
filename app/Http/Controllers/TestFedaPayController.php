<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\FedaPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TestFedaPayController extends Controller
{
    protected FedaPayService $fedaPayService;

    public function __construct(FedaPayService $fedaPayService)
    {
        $this->fedaPayService = $fedaPayService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Autoriser seulement en mode debug ou pour les admins
            if (!config('app.debug') && !auth()->user()->isAdmin()) {
                abort(403, 'Accès non autorisé aux outils de test');
            }
            return $next($request);
        });
    }

    /**
     * Afficher la page de test
     */
    public function index(): View
    {
        return view('test-fedapay');
    }

    /**
     * Tester la connexion à l'API FedaPay
     */
    public function testApiConnection(): JsonResponse
    {
        try {
            // Test simple de l'API en récupérant les informations du compte
            $response = $this->fedaPayService->makeApiCall('GET', '/account');
            
            if ($response && isset($response['id'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connexion API réussie',
                    'account_id' => $response['id'],
                    'account_name' => $response['name'] ?? 'N/A'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Réponse API invalide'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Test API FedaPay échoué: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester la création d'une transaction
     */
    public function testTransaction(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id'
            ]);

            $course = Course::findOrFail($request->course_id);
            $user = Auth::user();

            // Créer un paiement de test
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'currency' => 'EUR',
                'payment_gateway' => 'fedapay',
                'status' => 'pending',
                'transaction_id' => 'TEST_' . uniqid()
            ]);

            // Créer la transaction FedaPay
            $transaction = $this->fedaPayService->createTransaction($payment);

            if ($transaction) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction créée avec succès',
                    'transaction_id' => $transaction['reference'] ?? $transaction['id'],
                    'payment_id' => $payment->id,
                    'checkout_url' => $transaction['url'] ?? null
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Échec de création de la transaction'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Test transaction FedaPay échoué: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simuler un webhook FedaPay
     */
    public function testWebhook(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,declined,canceled'
            ]);

            // Récupérer le dernier paiement en attente
            $payment = Payment::where('status', 'pending')
                             ->where('payment_gateway', 'fedapay')
                             ->latest()
                             ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun paiement en attente trouvé pour le test'
                ], 404);
            }

            // Simuler les données du webhook
            $webhookData = [
                'entity' => 'event',
                'type' => 'transaction.updated',
                'data' => [
                    'id' => $payment->gateway_transaction_id ?? 'test_' . $payment->id,
                    'reference' => $payment->transaction_id,
                    'amount' => $payment->amount * 100, // FedaPay utilise les centimes
                    'status' => $request->status,
                    'created_at' => now()->toISOString(),
                    'updated_at' => now()->toISOString(),
                    'customer' => [
                        'email' => $payment->user->email,
                        'firstname' => explode(' ', $payment->user->name)[0] ?? '',
                        'lastname' => explode(' ', $payment->user->name)[1] ?? ''
                    ]
                ]
            ];

            // Traiter le webhook
            $result = $this->fedaPayService->processWebhook($webhookData);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Webhook simulé et traité avec succès',
                    'payment_status' => $payment->fresh()->status,
                    'webhook_data' => $webhookData
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du webhook'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Test webhook FedaPay échoué: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les détails d'un paiement
     */
    public function getPaymentDetails(Payment $payment): JsonResponse
    {
        try {
            return response()->json([
                'id' => $payment->id,
                'user_id' => $payment->user_id,
                'course_id' => $payment->course_id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'converted_amount' => $payment->converted_amount,
                'converted_currency' => $payment->converted_currency,
                'payment_gateway' => $payment->payment_gateway,
                'status' => $payment->status,
                'transaction_id' => $payment->transaction_id,
                'gateway_transaction_id' => $payment->gateway_transaction_id,
                'gateway_response' => $payment->gateway_response,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
                'user' => [
                    'name' => $payment->user->name,
                    'email' => $payment->user->email
                ],
                'course' => [
                    'title' => $payment->course->title,
                    'price' => $payment->course->price
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un utilisateur et un cours de test
     */
    public function createTestData(): JsonResponse
    {
        try {
            // Créer un utilisateur de test s'il n'existe pas
            $testUser = User::firstOrCreate([
                'email' => 'test.fedapay@example.com'
            ], [
                'name' => 'Utilisateur Test FedaPay',
                'password' => bcrypt('password123'),
                'email_verified_at' => now()
            ]);

            // Créer un cours de test s'il n'existe pas
            $testCourse = Course::firstOrCreate([
                'slug' => 'cours-test-fedapay'
            ], [
                'title' => 'Cours de Test FedaPay',
                'description' => 'Cours créé automatiquement pour tester l\'intégration FedaPay',
                'price' => 5000, // 50 EUR
                'is_published' => true,
                'difficulty_level' => 'beginner',
                'instructor_id' => $testUser->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Données de test créées',
                'test_user' => [
                    'id' => $testUser->id,
                    'email' => $testUser->email
                ],
                'test_course' => [
                    'id' => $testCourse->id,
                    'title' => $testCourse->title,
                    'price' => $testCourse->price
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Création données test échouée: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Nettoyer les données de test
     */
    public function cleanTestData(): JsonResponse
    {
        try {
            $deletedPayments = Payment::where('transaction_id', 'LIKE', 'TEST_%')->delete();
            
            // Optionnel: supprimer les données de test
            $testUser = User::where('email', 'test.fedapay@example.com')->first();
            $testCourse = Course::where('slug', 'cours-test-fedapay')->first();
            
            $deletedUsers = 0;
            $deletedCourses = 0;
            
            if ($testUser && $testUser->payments()->count() === 0) {
                $testUser->delete();
                $deletedUsers = 1;
            }
            
            if ($testCourse && $testCourse->payments()->count() === 0) {
                $testCourse->delete();
                $deletedCourses = 1;
            }

            return response()->json([
                'success' => true,
                'message' => 'Données de test nettoyées',
                'deleted' => [
                    'payments' => $deletedPayments,
                    'users' => $deletedUsers,
                    'courses' => $deletedCourses
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Nettoyage données test échoué: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les logs récents
     */
    public function getLogs(): JsonResponse
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!file_exists($logFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier de log non trouvé'
                ], 404);
            }

            // Lire les dernières lignes du fichier de log
            $lines = [];
            $file = new \SplFileObject($logFile, 'r');
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key();
            
            $startLine = max(0, $totalLines - 100); // 100 dernières lignes
            $file->seek($startLine);
            
            while (!$file->eof()) {
                $line = $file->fgets();
                if (stripos($line, 'fedapay') !== false || stripos($line, 'payment') !== false) {
                    $lines[] = trim($line);
                }
            }

            return response()->json([
                'success' => true,
                'logs' => array_slice($lines, -20) // 20 dernières entrées pertinentes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la lecture des logs: ' . $e->getMessage()
            ], 500);
        }
    }
}
