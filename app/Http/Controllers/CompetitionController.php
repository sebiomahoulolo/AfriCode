<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\Competition;
use App\Models\CompetitionTestCase;

class CompetitionController extends Controller
{
    /**
     * Exécute du code via l'API Piston
     */
    public function runCode(Request $request, $slug)
    {
        $request->validate([
            'code' => 'required|string',
            'language' => 'required|string|in:c,cpp,python,python3,javascript,php,java,html,css,sql,django',
        ]);

        $code = $request->input('code');
        $language = strtolower(trim($request->input('language')));

        Log::info("Exécution demandée - Langage: $language");

        // Configuration des langages avec timeouts optimisés
        $config = [
            'c' => [
                'version' => '10.2.0',
                'files' => [['name' => 'main.c', 'content' => $code]],
                'compile_timeout' => 10000,
                'run_timeout' => 5000
            ],
            'cpp' => [
                'version' => '10.2.0',
                'files' => [['name' => 'main.cpp', 'content' => $code]],
                'compile_timeout' => 10000,
                'run_timeout' => 5000
            ],
            'python' => [
                'version' => '3.10.0',
                'files' => [['content' => $code]],
                'run_timeout' => 5000
            ],
            'python3' => [
                'version' => '3.10.0',
                'files' => [['content' => $code]],
                'run_timeout' => 5000
            ],
            'javascript' => [
                'version' => '18.15.0',
                'files' => [['content' => $code]],
                'run_timeout' => 8000
            ],
            'php' => [
                'version' => '8.2.3',
                'files' => [['name' => 'index.php', 'content' => $code]],
                'run_timeout' => 5000
            ],
            'java' => [
                'version' => '15.0.2',
                'files' => [['name' => 'Main.java', 'content' => $code]],
                'compile_timeout' => 15000, // Augmenté à 15s
                'run_timeout' => 8000 // Augmenté à 8s
            ],
            'html' => [
                'version' => 'latest',
                'files' => [['name' => 'index.html', 'content' => $code]],
                'run_timeout' => 5000
            ],
            'css' => [
                'version' => 'latest',
                'files' => [['name' => 'style.css', 'content' => $code]],
                'run_timeout' => 5000
            ],
            'sql' => [
                'version' => 'latest',
                'files' => [['name' => 'query.sql', 'content' => $code]],
                'run_timeout' => 5000
            ],
            'django' => [
                'language' => 'python3',
                'version' => '3.10.0',
                'files' => [['content' => $code]],
                'run_timeout' => 5000
            ]
        ];

        // Construction du payload
        $payload = [
            'language' => ($language === 'django') ? 'python3' : $language,
            'version' => $config[$language]['version'] ?? 'latest',
            'files' => $config[$language]['files'] ?? [['content' => $code]],
            'stdin' => '',
            'run_timeout' => $config[$language]['run_timeout'] ?? 5000
        ];

        // Ajout des timeout de compilation si nécessaire
        if (isset($config[$language]['compile_timeout'])) {
            $payload['compile_timeout'] = $config[$language]['compile_timeout'];
        }

        try {
            $client = new Client([
                'timeout' => 30, // Timeout client augmenté à 30s
                'verify' => false,
                'http_errors' => false
            ]);

            Log::debug('Envoi à Piston API', ['payload' => $payload]);

            $response = $client->post('https://emkc.org/api/v2/piston/execute', [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            Log::debug('Réponse Piston', $result);

            // Formatage des résultats
            $output = $this->formatOutput($result, $language);
            $error = $this->formatError($result, $language);

            return response()->json([
                'success' => empty($error),
                'output' => $output,
                'error' => $error,
                'execution_time' => $result['run']['time'] ?? null,
                'raw_response' => $result // Pour débogage
            ]);

        } catch (RequestException $e) {
            Log::error("Erreur API Piston", [
                'error' => $e->getMessage(),
                'payload' => $payload,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur de connexion au service d\'exécution',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Évalue automatiquement une soumission sur tous les cas de test de la compétition
     */
    public function evaluateSubmission(Request $request, $slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();
        $testCases = $competition->testCases;

        $request->validate([
            'code' => 'required|string',
            'language' => 'required|string',
        ]);
        $code = $request->input('code');
        $language = $request->input('language');
        $results = [];

        foreach ($testCases as $testCase) {
            $payload = [
                'language' => $language,
                'version' => 'latest',
                'files' => [['content' => $code]],
                'stdin' => $testCase->input ?? '',
                'run_timeout' => 5000
            ];
            $result = $this->runCodeWithPayload($payload);
            $output = trim($result['output'] ?? '');
            $expected = trim($testCase->expected_output);

            $results[] = [
                'input' => $testCase->input,
                'expected' => $expected,
                'output' => $output,
                'success' => $output === $expected,
                'error' => $result['error'] ?? null,
            ];
        }

        return response()->json([
            'success' => collect($results)->every(fn($r) => $r['success']),
            'results' => $results,
        ]);
    }

    /**
     * Exécute le code avec un payload personnalisé (utilitaire interne)
     */
    private function runCodeWithPayload(array $payload)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 30,
                'verify' => false,
                'http_errors' => false
            ]);
            $response = $client->post('https://emkc.org/api/v2/piston/execute', [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            $result = json_decode($response->getBody(), true);
            return [
                'output' => $result['run']['output'] ?? $result['output'] ?? '',
                'error' => $result['run']['stderr'] ?? $result['stderr'] ?? '',
            ];
        } catch (\Exception $e) {
            return [
                'output' => '',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Formate la sortie standard
     */
    private function formatOutput(array $result, string $language): string
    {
        // Traitement spécial pour JavaScript
        if ($language === 'javascript') {
            $output = $result['run']['stdout'] ?? $result['run']['output'] ?? '';
            return trim($output) ?: 'Aucune sortie';
        }

        $output = $result['run']['output'] ?? $result['output'] ?? '';
        
        if ($language === 'c' || $language === 'cpp') {
            $compileOutput = $result['compile']['output'] ?? '';
            return $compileOutput ? "Compilation:\n$compileOutput\n\nExécution:\n$output" : $output;
        }

        return trim($output) ?: 'Aucune sortie';
    }

    /**
     * Formate les erreurs
     */
    private function formatError(array $result, string $language): string
    {
        $error = $result['run']['stderr'] ?? $result['stderr'] ?? '';
        $compileError = $result['compile']['stderr'] ?? '';

        // Vérification des signaux d'erreur
        if (isset($result['run']['signal'])) {
            $signal = $result['run']['signal'];
            if ($signal === 'SIGKILL') {
                $error = "Timeout: Le code a pris trop de temps à s'exécuter (signal SIGKILL)";
            } elseif ($signal) {
                $error = "Erreur d'exécution (signal $signal)";
            }
        }

        if ($language === 'c' || $language === 'cpp') {
            $error = $this->cleanCErrors($error);
            $compileError = $this->cleanCErrors($compileError);
            return $compileError ? "Erreurs de compilation:\n$compileError\n\nErreurs d'exécution:\n$error" : $error;
        }

        return trim($error);
    }

    /**
     * Nettoie les messages d'erreur C/C++
     */
    private function cleanCErrors(string $error): string
    {
        return preg_replace([
            '/\/tmp\/[^:]+:/',
            '/main\.(c|cpp):/',
            '/\/piston\/[^:]+:/'
        ], '', $error);
    }
}