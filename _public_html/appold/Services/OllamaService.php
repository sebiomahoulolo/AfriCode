<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->baseUrl = config('ai.ollama_url', 'http://127.0.0.1:11434');
        $this->model = config('ai.ollama_model', 'mistral');
    }

    public function generate(string $prompt, array $context = []): string
    {
        $fullPrompt = $this->buildPrompt($prompt, $context);

        $response = Http::timeout(120)->post("{$this->baseUrl}/api/generate", [
            'model' => $this->model,
            'prompt' => $fullPrompt,
            'stream' => false,
            'options' => [
                'temperature' => 0.7,
                'top_p' => 0.9,
                'num_predict' => 500,
            ],
        ]);

        if ($response->successful()) {
            return (string) $response->json('response', '');
        }

        throw new \RuntimeException('Ollama: échec de génération');
    }

    private function buildPrompt(string $userMessage, array $context): string
    {
        $role = $context['role'] ?? 'apprenant';
        
        if ($role === 'formateur') {
            $systemPrompt = "Tu es AfriCode AI, l'assistant intelligent d'AfriCode spécialisé pour les formateurs.\n\n";
            $systemPrompt .= "Tu aides les formateurs à :\n";
            $systemPrompt .= "- Créer et améliorer leurs cours\n";
            $systemPrompt .= "- Augmenter leurs revenus et ventes\n";
            $systemPrompt .= "- Gérer leurs étudiants\n";
            $systemPrompt .= "- Optimiser leur contenu pédagogique\n";
            $systemPrompt .= "- Comprendre les fonctionnalités de la plateforme\n\n";
            $systemPrompt .= "Règles :\n- Reste dans le contexte éducation/formation AfriCode\n";
            $systemPrompt .= "- Réponds en français, clair et pédagogique\n";
            $systemPrompt .= "- Sois bienveillant, encourageant et pratique\n";
            $systemPrompt .= "- Donne des conseils concrets et actionnables";
            
            $intent = $context['intent'] ?? 'general';
            $ctx = "Contexte formateur:\n- Rôle: formateur\n- Intention: {$intent}";
            
            if (isset($context['formateur_info'])) {
                $info = $context['formateur_info'];
                $ctx .= "\n- Cours créés: {$info['courses_count']}";
                $ctx .= "\n- Cours publiés: {$info['published_courses']}";
                $ctx .= "\n- Revenus totaux: " . number_format($info['total_earnings'], 0, ',', ' ') . " FCFA";
                $ctx .= "\n- Revenus en attente: " . number_format($info['pending_earnings'], 0, ',', ' ') . " FCFA";
            }
        } else {
            $systemPrompt = "Tu es AfriCode AI, l'assistant intelligent d'AfriCode.\n\nRègles :\n- Reste dans le contexte éducation/formation AfriCode\n- Réponds en français, clair et pédagogique\n- Sois bienveillant et concis";
            $intent = $context['intent'] ?? 'general';
            $ctx = "Contexte utilisateur:\n- Rôle: {$role}\n- Intention: {$intent}";
        }

        return $systemPrompt . "\n\n" . $ctx . "\n\nUtilisateur: " . $userMessage . "\n\nAfriCode AI:";
    }

    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/tags");
            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }
}


