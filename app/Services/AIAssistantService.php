<?php

namespace App\Services;

use App\Models\AIConversation;
use App\Models\User;

class AIAssistantService
{
    public function __construct(
        private readonly OllamaService $ollamaService,
        private readonly GroqService $groqService,
    )
    {
    }

    public function chat(User $user, string $message): string
    {
        $conversation = $this->getOrCreateConversation($user);
        $conversation->addMessage('user', $message);

        $intent = $this->analyzeIntent($message);
        $context = $this->buildContext($user, $intent, $message);

        $response = $this->generateResponse($conversation, $context);
        $conversation->addMessage('assistant', $response);
        $conversation->updateContext($context);

        return $response;
    }

    private function getOrCreateConversation(User $user): AIConversation
    {
        return AIConversation::firstOrCreate(
            ['user_id' => $user->id, 'session_id' => session()->getId()],
            ['messages' => [], 'context' => []]
        );
    }

    private function analyzeIntent(string $message): string
    {
        $m = mb_strtolower($message);
        if (str_contains($m, 'cours') || str_contains($m, 'apprendre')) return 'course_search';
        if (str_contains($m, 'parcours') || str_contains($m, 'devenir')) return 'learning_path';
        if (str_contains($m, 'problème') || str_contains($m, 'aide')) return 'technical_support';
        return 'general_question';
    }

    private function buildContext(User $user, string $intent, string $message): array
    {
        $role = method_exists($user, 'getAttribute') ? (string) ($user->role ?? 'apprenant') : 'apprenant';
        
        $context = [
            'user_id' => $user->id,
            'role' => $role,
            'intent' => $intent,
        ];
        
        // Ajouter des informations spécifiques pour les formateurs
        if ($role === 'formateur') {
            $context['formateur_info'] = [
                'courses_count' => $user->coursesInstructed()->count(),
                'published_courses' => $user->coursesInstructed()->where('status', 'published')->count(),
                'total_earnings' => $user->total_earnings ?? 0,
                'pending_earnings' => $user->pending_earnings ?? 0,
            ];
        }
        
        return $context;
    }

    private function generateResponse(AIConversation $conversation, array $context): string
    {
        $messages = $conversation->messages ?? [];
        $contextString = $this->buildContextString($context);
        $fullMessage = $this->buildFullMessage($messages, $contextString);
        $provider = config('ai.provider', 'ollama');
        if ($provider === 'groq') {
            return $this->groqService->generate($fullMessage, $context);
        }
        return $this->ollamaService->generate($fullMessage, $context);
    }

    private function buildContextString(array $context): string
    {
        $role = $context['role'] ?? 'apprenant';
        $out = "Contexte utilisateur:\n";
        $out .= "- Rôle : {$role}\n";
        $out .= "- Intention : " . ($context['intent'] ?? 'general') . "\n";
        
        if ($role === 'formateur' && isset($context['formateur_info'])) {
            $info = $context['formateur_info'];
            $out .= "- Cours créés : {$info['courses_count']}\n";
            $out .= "- Cours publiés : {$info['published_courses']}\n";
            $out .= "- Revenus totaux : " . number_format($info['total_earnings'], 0, ',', ' ') . " FCFA\n";
        }
        
        return $out;
    }

    private function buildFullMessage(array $messages, string $contextString): string
    {
        $full = $contextString . "\n\n";
        foreach (array_slice($messages, -5) as $msg) {
            $full .= ($msg['role'] ?? 'user') . ': ' . ($msg['content'] ?? '') . "\n";
        }
        return $full;
    }
}


