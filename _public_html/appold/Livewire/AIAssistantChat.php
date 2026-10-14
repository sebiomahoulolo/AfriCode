<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AIAssistantService;
use App\Models\AIConversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AIAssistantChat extends Component
{
    public array $messages = [];
    public string $input = '';
    public bool $loading = false;
    public array $suggestions = [];

    public function getSuggestionsProperty(): array
    {
        $user = Auth::user();
        $role = $user->role ?? 'apprenant';
        
        if ($role === 'formateur') {
            return [
                'Comment créer un cours efficace ?',
                'Comment améliorer mes cours ?',
                'Comment augmenter mes revenus ?',
                'Comment gérer mes étudiants ?',
                'Comment optimiser mes cours pour les ventes ?',
            ];
        }
        
        return [
            'Je veux apprendre Laravel',
            'Quels cours me recommandez-vous ?',
            'Comment créer un cours ?',
            "Qu'est-ce que la gamification ?",
            'Comment obtenir un certificat ?',
        ];
    }

    protected $listeners = ['sendMessage'];

    public function mount(): void
    {
        // Initialiser les suggestions selon le rôle
        $this->suggestions = $this->getSuggestionsProperty();
        
        try {
            // Vérifier si la table existe avant de faire la requête
            if (!\Illuminate\Support\Facades\Schema::hasTable('ai_conversations')) {
                $this->messages = [];
                return;
            }
            
            $conversation = AIConversation::where('user_id', Auth::id())
                ->where('session_id', session()->getId())
                ->first();

            if ($conversation && $conversation->messages) {
                $this->messages = $conversation->messages;
            }
        } catch (\Exception $e) {
            // Si la table n'existe pas ou autre erreur, initialiser avec un tableau vide
            $this->messages = [];
        }
    }

    public function sendMessage(?string $message = null): void
    {
        $messageText = $message ?? $this->input;
        if (trim((string) $messageText) === '') {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $messageText,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->input = '';
        $this->loading = true;

        try {
            $aiService = app(AIAssistantService::class);
            $response = $aiService->chat(Auth::user(), $messageText);

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response,
                'timestamp' => now()->toDateTimeString(),
            ];
        } catch (\Throwable $e) {
            \Log::error('AI Assistant Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'message' => $messageText,
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Désolé, une erreur est survenue. ';
            
            // Messages d'erreur plus spécifiques
            if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'timeout')) {
                $errorMessage .= 'Le service AI n\'est pas disponible pour le moment. Veuillez vérifier votre connexion ou réessayer plus tard.';
            } elseif (str_contains($e->getMessage(), 'Ollama') || str_contains($e->getMessage(), 'Groq')) {
                $errorMessage .= 'Le service AI n\'est pas configuré correctement. Contactez le support technique.';
            } else {
                $errorMessage .= 'Veuillez réessayer dans quelques instants.';
            }
            
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $errorMessage,
                'timestamp' => now()->toDateTimeString(),
            ];
        } finally {
            $this->loading = false;
        }

        $this->dispatch('scroll-to-bottom');
    }

    public function render()
    {
        $this->suggestions = $this->getSuggestionsProperty();
        return view('livewire.ai-assistant-chat');
    }
}


