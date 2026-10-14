<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('ai.groq_api_key');
        $this->model = (string) config('ai.groq_model', 'llama-3.1-8b-instant');
    }

    public function generate(string $prompt, array $context = []): string
    {
        $fullPrompt = $this->buildPrompt($prompt, $context);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => "Tu es AfriCode AI, l'assistant d'AfriCode. Réponds en français."],
                ['role' => 'user', 'content' => $fullPrompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 800,
        ]);

        if ($response->successful()) {
            return (string) $response->json('choices.0.message.content', '');
        }

        throw new \RuntimeException('Groq: échec de génération');
    }

    private function buildPrompt(string $userMessage, array $context): string
    {
        $role = $context['role'] ?? 'apprenant';
        $intent = $context['intent'] ?? 'general';
        
        if ($role === 'formateur') {
            $systemPrompt = "Tu es AfriCode AI, l'assistant intelligent d'AfriCode spécialisé pour les formateurs.\n\n";
            $systemPrompt .= "Tu aides les formateurs à créer des cours, augmenter leurs revenus, gérer leurs étudiants et optimiser leur contenu.\n";
            $systemPrompt .= "Reste dans le contexte éducation/formation AfriCode. Réponds en français, clair et pratique.";
            
            $ctx = "Contexte formateur:\n- Rôle: formateur\n- Intention: {$intent}";
            
            if (isset($context['formateur_info'])) {
                $info = $context['formateur_info'];
                $ctx .= "\n- Cours créés: {$info['courses_count']}";
                $ctx .= "\n- Cours publiés: {$info['published_courses']}";
                $ctx .= "\n- Revenus totaux: " . number_format($info['total_earnings'], 0, ',', ' ') . " FCFA";
            }
        } else {
            $systemPrompt = "Tu es AfriCode AI, l'assistant intelligent d'AfriCode. Réponds en français.";
            $ctx = "Contexte utilisateur:\n- Rôle: {$role}\n- Intention: {$intent}";
        }
        
        return $systemPrompt . "\n\n" . $ctx . "\n\nUtilisateur: " . $userMessage;
    }
}


