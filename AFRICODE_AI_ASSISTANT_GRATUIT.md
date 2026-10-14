# 🤖 AfriCode AI Assistant - Version Gratuite & Laravel Pur

## 📋 Table des Matières
1. [Vue d'Ensemble](#vue-densemble)
2. [APIs Gratuites](#apis-gratuites)
3. [Architecture Laravel Pure](#architecture-laravel-pure)
4. [Implémentation](#implémentation)
5. [Intégration Non-Invasive](#intégration-non-invasive)
6. [Alternatives Gratuites](#alternatives-gratuites)
7. [Déploiement](#déploiement)

---

## 🎯 Vue d'Ensemble

### Approche Moderne
- ✅ **100% Laravel** : Pas de React/Vue, uniquement Blade + Livewire
- ✅ **APIs Gratuites** : Utilisation d'APIs open-source et gratuites
- ✅ **Non-Invasive** : S'intègre sans casser votre architecture actuelle
- ✅ **Léger** : Pas de dépendances lourdes
- ✅ **Performant** : Cache et optimisation intégrés

### Philosophie
Rester dans l'écosystème Laravel que vous connaissez déjà, sans ajouter de complexité.

---

## 🆓 APIs Gratuites

### Option 1 : Ollama (RECOMMANDÉ) ⭐

#### Avantages
- ✅ **100% Gratuit** et open-source
- ✅ **Local** : Tourne sur votre serveur
- ✅ **Pas de limite** de requêtes
- ✅ **Confidentialité** : Vos données restent chez vous
- ✅ **Rapide** : Pas de latence réseau
- ✅ **Plusieurs modèles** : Llama 2, Mistral, Codellama, etc.

#### Installation
```bash
# Sur Ubuntu/Debian
curl -fsSL https://ollama.ai/install.sh | sh

# Démarrer le service
ollama serve

# Télécharger un modèle (Llama 2)
ollama pull llama2

# Tester
ollama run llama2 "Bonjour, comment ça va ?"
```

#### Modèles Disponibles
```bash
ollama pull llama2          # 7B parameters - Léger
ollama pull llama2:13b      # 13B parameters - Plus puissant
ollama pull mistral         # Mistral 7B - Excellent
ollama pull codellama       # Spécialisé pour le code
ollama pull qwen           # Alibaba Qwen - Multilingue
```

#### Coûts
- **$0/mois** - Gratuit à 100%
- Utilise votre CPU/GPU local
- Pas de limite de requêtes

---

### Option 2 : Hugging Face (Gratuit avec Limitations)

#### Avantages
- ✅ **Gratuit** jusqu'à 1000 requêtes/mois
- ✅ **Cloud** : Pas besoin d'installer
- ✅ **Plusieurs modèles** disponibles
- ✅ **API simple** à utiliser

#### Modèles Recommandés
```php
// Mistral 7B (Gratuit)
'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.1'

// Llama 2 (Gratuit)
'https://api-inference.huggingface.co/models/meta-llama/Llama-2-7b-chat-hf'

// Qwen (Gratuit, Multilingue)
'https://api-inference.huggingface.co/models/Qwen/Qwen-7B-Chat'
```

#### Coûts
- **$0/mois** - 1000 requêtes gratuites
- Après : $0.0002 par requête

---

### Option 3 : Groq (RECOMMANDÉ pour Performance) ⭐

#### Avantages
- ✅ **Gratuit** avec limitations généreuses
- ✅ **Ultra rapide** : 10-100x plus rapide que OpenAI
- ✅ **API simple** : Compatible OpenAI
- ✅ **Llama 2 et Mistral** disponibles

#### Limites Gratuites
- **30 requêtes/minute**
- **14,400 requêtes/jour**
- Suffisant pour la plupart des cas d'usage

#### Coûts
- **$0/mois** pour les limites ci-dessus
- Après : Pay-as-you-go

---

### Option 4 : OpenRouter (Agrégateur)

#### Avantages
- ✅ **Plusieurs modèles** gratuits
- ✅ **API unifiée** : Changez de modèle facilement
- ✅ **Fallback** : Si un modèle échoue, utilise un autre

#### Modèles Gratuits
```php
'google/gemini-flash-1.5'      // Google Gemini
'google/gemini-flash-1.5-8b'   // Version plus petite
'meta-llama/llama-3.1-8b'      // Llama 3.1
'mistralai/mistral-7b'         // Mistral 7B
```

#### Coûts
- **$0/mois** pour les modèles gratuits
- Limites variables selon le modèle

---

### Comparaison

| Solution | Gratuit | Performance | Confidentialité | Installation |
|----------|---------|-------------|-----------------|--------------|
| **Ollama** | ✅ 100% | ⭐⭐⭐⭐ | ✅ Excellente | ⚙️ Serveur |
| **Hugging Face** | ✅ 1000 req/mois | ⭐⭐⭐ | ⚠️ Cloud | ✅ Aucune |
| **Groq** | ✅ 30 req/min | ⭐⭐⭐⭐⭐ | ⚠️ Cloud | ✅ Aucune |
| **OpenRouter** | ✅ Variable | ⭐⭐⭐⭐ | ⚠️ Cloud | ✅ Aucune |

---

## 🏗️ Architecture Laravel Pure

### Architecture Simplifiée

```
┌─────────────────────────────────────────────────────────┐
│              Blade Templates (UI)                        │
│  - Interface chat intégrée dans les pages existantes    │
│  - Livewire pour l'interactivité                         │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              Livewire Components                         │
│  - AIAssistantChat.php (Composant principal)            │
│  - Gestion de l'état et interactions                    │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              Controllers Laravel                         │
│  - AIAssistantController.php                            │
│  - API endpoints pour l'IA                              │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              Services Laravel                            │
│  - AIAssistantService.php                               │
│  - OllamaService / HuggingFaceService                   │
│  - KnowledgeBaseService.php                             │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              API Externe (Gratuite)                      │
│  - Ollama (local) / Groq / Hugging Face                 │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              Database (PostgreSQL)                       │
│  - ai_conversations (historique)                        │
│  - ai_knowledge_base (connaissances)                    │
└─────────────────────────────────────────────────────────┘
```

### Intégration Non-Invasive

```
Votre Projet Actuel
├── app/
│   ├── Http/Controllers/
│   │   └── AIAssistantController.php  ← NOUVEAU
│   ├── Livewire/
│   │   └── AIAssistantChat.php        ← NOUVEAU
│   ├── Services/
│   │   ├── AIAssistantService.php     ← NOUVEAU
│   │   └── OllamaService.php          ← NOUVEAU
│   └── Models/
│       └── AIConversation.php         ← NOUVEAU
├── database/migrations/
│   └── create_ai_tables.php           ← NOUVEAU
├── resources/views/
│   └── components/
│       └── ai-assistant.blade.php     ← NOUVEAU
└── routes/
    └── web.php                        ← MODIFIÉ (ajout 1 ligne)
```

**Aucun fichier existant n'est modifié !** ✅

---

## 💻 Implémentation

### 1. Installation Ollama (Option 1 - Recommandée)

```bash
# Sur Ubuntu/Debian
curl -fsSL https://ollama.ai/install.sh | sh

# Démarrer le service
sudo systemctl start ollama
sudo systemctl enable ollama

# Télécharger un modèle
ollama pull llama2

# Tester
ollama run llama2 "Bonjour, je suis AfriCode AI"
```

### 2. Migrations

```php
<?php
// database/migrations/2024_01_15_create_ai_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->index();
            $table->json('messages')->nullable();
            $table->json('context')->nullable();
            $table->string('intent')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ai_knowledge_base');
    }
};
```

### 3. Model AIConversation

```php
<?php
// app/Models/AIConversation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIConversation extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'messages',
        'context',
        'intent',
    ];

    protected $casts = [
        'messages' => 'array',
        'context' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addMessage(string $role, string $content): void
    {
        $messages = $this->messages ?? [];
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        $this->update(['messages' => $messages]);
    }

    public function getContext(): array
    {
        return $this->context ?? [];
    }

    public function updateContext(array $context): void
    {
        $this->update(['context' => $context]);
    }
}
```

### 4. Service Ollama

```php
<?php
// app/Services/OllamaService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OllamaService
{
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->baseUrl = config('ai.ollama_url', 'http://localhost:11434');
        $this->model = config('ai.ollama_model', 'llama2');
    }

    /**
     * Génère une réponse avec Ollama
     */
    public function generate(string $prompt, array $context = []): string
    {
        // Construire le prompt complet avec le contexte
        $fullPrompt = $this->buildPrompt($prompt, $context);

        // Appeler l'API Ollama
        $response = Http::timeout(120)
            ->post("{$this->baseUrl}/api/generate", [
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
            return $response->json('response');
        }

        throw new \Exception('Erreur lors de la génération de la réponse');
    }

    /**
     * Construit le prompt avec le contexte AfriCode
     */
    private function buildPrompt(string $userMessage, array $context): string
    {
        $systemPrompt = "Tu es AfriCode AI, l'assistant intelligent de la plateforme d'apprentissage AfriCode.

Règles importantes :
1. Tu restes TOUJOURS dans le contexte d'AfriCode et de l'éducation en ligne
2. Tu ne réponds QUE sur les sujets liés à l'apprentissage, aux cours, à la formation
3. Si on te pose une question hors contexte, tu rediriges vers le support AfriCode
4. Tu es bienveillant, encourageant et pédagogique
5. Tu utilises des emojis pour rendre tes réponses plus engageantes

Contexte utilisateur :
- Rôle : {$context['role'] ?? 'apprenant'}
- Intention : {$context['intent'] ?? 'general'}

Base de connaissances AfriCode :
- Cours disponibles dans toutes les catégories (Développement, Design, Marketing, etc.)
- Système de badges et gamification
- Certificats PDF vérifiables
- Forum de discussion
- Support 24/7
- Prix en FCFA (Franc CFA)

Réponds en français de manière naturelle et engageante.";

        return "{$systemPrompt}\n\nUtilisateur: {$userMessage}\n\nAfriCode AI:";
    }

    /**
     * Vérifie si Ollama est disponible
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->baseUrl}/api/tags");
            
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Liste les modèles disponibles
     */
    public function listModels(): array
    {
        $response = Http::get("{$this->baseUrl}/api/tags");
        
        if ($response->successful()) {
            return collect($response->json('models'))
                ->pluck('name')
                ->toArray();
        }

        return [];
    }
}
```

### 5. Service Groq (Alternative)

```php
<?php
// app/Services/GroqService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('ai.groq_api_key');
        $this->model = config('ai.groq_model', 'llama-3.1-8b-instant');
    }

    public function generate(string $prompt, array $context = []): string
    {
        $fullPrompt = $this->buildPrompt($prompt, $context);

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es AfriCode AI, l\'assistant intelligent de la plateforme d\'apprentissage AfriCode.',
                ],
                [
                    'role' => 'user',
                    'content' => $fullPrompt,
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        throw new \Exception('Erreur lors de la génération de la réponse');
    }

    private function buildPrompt(string $userMessage, array $context): string
    {
        // Même logique que OllamaService
        return $userMessage;
    }
}
```

### 6. Service AIAssistant Principal

```php
<?php
// app/Services/AIAssistantService.php

namespace App\Services;

use App\Models\AIConversation;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;

class AIAssistantService
{
    private OllamaService $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    /**
     * Chat avec l'assistant IA
     */
    public function chat(User $user, string $message): string
    {
        // Récupérer ou créer la conversation
        $conversation = $this->getOrCreateConversation($user);
        
        // Ajouter le message de l'utilisateur
        $conversation->addMessage('user', $message);
        
        // Analyser l'intention
        $intent = $this->analyzeIntent($message);
        
        // Récupérer le contexte
        $context = $this->buildContext($user, $intent, $message);
        
        // Générer la réponse
        $response = $this->generateResponse($conversation, $context);
        
        // Ajouter la réponse de l'IA
        $conversation->addMessage('assistant', $response);
        
        // Mettre à jour le contexte
        $conversation->updateContext($context);
        
        return $response;
    }

    private function getOrCreateConversation(User $user): AIConversation
    {
        return AIConversation::firstOrCreate(
            [
                'user_id' => $user->id,
                'session_id' => session()->getId(),
            ],
            [
                'messages' => [],
                'context' => [],
            ]
        );
    }

    private function analyzeIntent(string $message): string
    {
        // Analyse simple basée sur des mots-clés
        $messageLower = strtolower($message);
        
        if (str_contains($messageLower, 'cours') || str_contains($messageLower, 'apprendre')) {
            return 'course_search';
        }
        
        if (str_contains($messageLower, 'créer') || str_contains($messageLower, 'création')) {
            return 'content_creation';
        }
        
        if (str_contains($messageLower, 'parcours') || str_contains($messageLower, 'devenir')) {
            return 'learning_path';
        }
        
        if (str_contains($messageLower, 'problème') || str_contains($messageLower, 'aide')) {
            return 'technical_support';
        }
        
        return 'general_question';
    }

    private function buildContext(User $user, string $intent, string $message): array
    {
        $context = [
            'user_id' => $user->id,
            'role' => $user->role,
            'intent' => $intent,
        ];

        // Ajouter des informations spécifiques selon l'intention
        if ($intent === 'course_search') {
            $context['user_courses'] = $user->enrollments()
                ->with('course')
                ->get()
                ->pluck('course.title')
                ->toArray();
            
            $context['user_level'] = $this->determineUserLevel($user);
        }

        return $context;
    }

    private function generateResponse(AIConversation $conversation, array $context): string
    {
        // Récupérer les messages précédents
        $messages = $conversation->messages ?? [];
        
        // Construire le contexte pour le prompt
        $contextString = $this->buildContextString($context);
        
        // Construire le message complet
        $fullMessage = $this->buildFullMessage($messages, $contextString);
        
        // Générer la réponse avec Ollama
        return $this->ollamaService->generate($fullMessage, $context);
    }

    private function buildContextString(array $context): string
    {
        $contextString = "Contexte utilisateur :\n";
        $contextString .= "- Rôle : {$context['role']}\n";
        $contextString .= "- Intention : {$context['intent']}\n";
        
        if (isset($context['user_level'])) {
            $contextString .= "- Niveau : {$context['user_level']}\n";
        }
        
        if (isset($context['user_courses']) && !empty($context['user_courses'])) {
            $contextString .= "- Cours suivis : " . implode(', ', $context['user_courses']) . "\n";
        }
        
        return $contextString;
    }

    private function buildFullMessage(array $messages, string $contextString): string
    {
        $fullMessage = "{$contextString}\n\n";
        
        // Ajouter les derniers messages (limite à 5)
        $recentMessages = array_slice($messages, -5);
        
        foreach ($recentMessages as $msg) {
            $fullMessage .= "{$msg['role']}: {$msg['content']}\n";
        }
        
        return $fullMessage;
    }

    private function determineUserLevel(User $user): string
    {
        $completedCourses = $user->enrollments()
            ->where('status', 'completed')
            ->count();
        
        if ($completedCourses === 0) {
            return 'débutant';
        } elseif ($completedCourses < 5) {
            return 'intermédiaire';
        } else {
            return 'avancé';
        }
    }

    /**
     * Recommande des cours
     */
    public function recommendCourses(User $user, array $criteria = []): array
    {
        $level = $criteria['level'] ?? $this->determineUserLevel($user);
        $category = $criteria['category'] ?? null;
        $budget = $criteria['budget'] ?? null;

        $query = Course::where('status', 'published');

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($budget !== null) {
            if ($budget === 'free') {
                $query->where('is_free', true);
            } else {
                $query->where('price', '<=', $budget);
            }
        }

        // Exclure les cours déjà suivis
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        $query->whereNotIn('id', $enrolledCourseIds);

        return $query->inRandomOrder()->limit(5)->get()->toArray();
    }

    /**
     * Génère un parcours d'apprentissage
     */
    public function generateLearningPath(User $user, string $goal): array
    {
        // Cache pour éviter les appels répétés
        return Cache::remember("learning_path_{$user->id}_{$goal}", 3600, function () use ($user, $goal) {
            $prompt = "Crée un parcours d'apprentissage pour quelqu'un qui veut : {$goal}
            
L'utilisateur est {$user->role} et a suivi {$user->enrollments()->count()} cours.
            
Propose un parcours structuré avec :
- Étapes claires
- Cours recommandés
- Durée estimée
- Budget total
- Objectifs à atteindre

Réponds en JSON avec cette structure :
{
  \"title\": \"Titre du parcours\",
  \"goal\": \"Objectif final\",
  \"duration\": \"Durée en mois\",
  \"steps\": [
    {
      \"step\": 1,
      \"title\": \"Titre de l'étape\",
      \"courses\": [\"Titre cours 1\", \"Titre cours 2\"],
      \"duration\": \"Durée\",
      \"objectives\": [\"Objectif 1\", \"Objectif 2\"]
    }
  ],
  \"total_budget\": \"Budget total\",
  \"final_objective\": \"Ce que l'utilisateur saura faire\"
}";

            $response = $this->ollamaService->generate($prompt, ['role' => $user->role]);
            
            // Extraire le JSON de la réponse
            preg_match('/\{[\s\S]*\}/', $response, $matches);
            
            if (!empty($matches[0])) {
                return json_decode($matches[0], true);
            }

            // Fallback si pas de JSON valide
            return [
                'title' => 'Parcours personnalisé',
                'goal' => $goal,
                'steps' => [],
            ];
        });
    }
}
```

### 7. Livewire Component

```php
<?php
// app/Livewire/AIAssistantChat.php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AIAssistantService;
use App\Models\AIConversation;
use Illuminate\Support\Facades\Auth;

class AIAssistantChat extends Component
{
    public $messages = [];
    public $input = '';
    public $loading = false;
    public $suggestions = [
        "Je veux apprendre Laravel",
        "Quels cours me recommandez-vous ?",
        "Comment créer un cours ?",
        "Qu'est-ce que la gamification ?",
        "Comment obtenir un certificat ?",
    ];

    protected $listeners = ['sendMessage'];

    public function mount()
    {
        // Charger l'historique si existe
        $conversation = AIConversation::where('user_id', Auth::id())
            ->where('session_id', session()->getId())
            ->first();

        if ($conversation && $conversation->messages) {
            $this->messages = $conversation->messages;
        }
    }

    public function sendMessage($message = null)
    {
        $messageText = $message ?? $this->input;
        
        if (empty(trim($messageText))) {
            return;
        }

        // Ajouter le message de l'utilisateur
        $this->messages[] = [
            'role' => 'user',
            'content' => $messageText,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->input = '';
        $this->loading = true;

        try {
            // Appeler le service IA
            $aiService = app(AIAssistantService::class);
            $response = $aiService->chat(Auth::user(), $messageText);

            // Ajouter la réponse de l'IA
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response,
                'timestamp' => now()->toDateTimeString(),
            ];
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Désolé, une erreur est survenue. Veuillez réessayer.',
                'timestamp' => now()->toDateTimeString(),
            ];
        } finally {
            $this->loading = false;
        }

        // Scroll vers le bas
        $this->dispatch('scroll-to-bottom');
    }

    public function render()
    {
        return view('livewire.ai-assistant-chat');
    }
}
```

### 8. Vue Livewire

```blade
{{-- resources/views/livewire/ai-assistant-chat.blade.php --}}

<div class="ai-assistant-container" x-data="{ showChat: false }">
    <!-- Bouton flottant -->
    <button 
        @click="showChat = !showChat"
        class="ai-assistant-toggle"
        x-show="!showChat"
    >
        <span class="ai-icon">🤖</span>
        <span class="ai-label">AfriCode AI</span>
    </button>

    <!-- Interface de chat -->
    <div 
        class="ai-assistant-window"
        x-show="showChat"
        x-transition
    >
        <!-- Header -->
        <div class="ai-header">
            <div class="ai-avatar">
                <span>🤖</span>
            </div>
            <div class="ai-info">
                <h3>AfriCode AI Assistant</h3>
                <p>Comment puis-je vous aider ?</p>
            </div>
            <button @click="showChat = false" class="ai-close">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div class="ai-messages" id="messages-container">
            @if(empty($messages))
                <div class="welcome-message">
                    <h4>👋 Bienvenue !</h4>
                    <p>Je suis AfriCode AI, votre assistant intelligent.</p>
                    <p>Je peux vous aider à :</p>
                    <ul>
                        <li>🎓 Trouver des cours adaptés</li>
                        <li>📚 Créer un parcours d'apprentissage</li>
                        <li>💡 Obtenir des recommandations</li>
                        <li>🆘 Résoudre vos problèmes</li>
                    </ul>
                    <p class="mt-4 font-semibold">Essayez une de ces questions :</p>
                </div>
            @endif

            @foreach($messages as $message)
                <div class="message {{ $message['role'] === 'user' ? 'user-message' : 'ai-message' }}">
                    <div class="message-content">
                        @if($message['role'] === 'assistant')
                            <div class="ai-avatar-small">🤖</div>
                        @endif
                        <div class="message-text">
                            {{ $message['content'] }}
                        </div>
                    </div>
                    <div class="message-time">
                        {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}
                    </div>
                </div>
            @endforeach

            @if($loading)
                <div class="message ai-message">
                    <div class="message-content">
                        <div class="ai-avatar-small">🤖</div>
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Suggestions -->
        @if(empty($messages))
            <div class="ai-suggestions">
                @foreach($suggestions as $suggestion)
                    <button 
                        wire:click="sendMessage('{{ $suggestion }}')"
                        class="suggestion-button"
                    >
                        {{ $suggestion }}
                    </button>
                @endforeach
            </div>
        @endif

        <!-- Input -->
        <form wire:submit.prevent="sendMessage" class="ai-input-form">
            <input
                type="text"
                wire:model="input"
                placeholder="Posez votre question..."
                class="ai-input"
                @keydown.enter.prevent="sendMessage"
            >
            <button type="submit" class="ai-send-button" wire:loading.attr="disabled">
                <span wire:loading.remove>Envoyer</span>
                <span wire:loading>...</span>
                <span>✈️</span>
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
/* Styles CSS - À ajouter dans votre fichier CSS principal */
.ai-assistant-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.ai-assistant-toggle {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(30, 163, 139, 0.3);
    transition: all 0.3s;
}

.ai-assistant-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(30, 163, 139, 0.4);
}

.ai-icon {
    font-size: 1.5rem;
}

.ai-label {
    font-weight: 600;
}

.ai-assistant-window {
    width: 400px;
    height: 600px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.ai-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    color: white;
}

.ai-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.ai-info {
    flex: 1;
}

.ai-info h3 {
    margin: 0;
    font-size: 1.25rem;
}

.ai-info p {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 0.875rem;
}

.ai-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.ai-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.ai-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    background: #f9fafb;
}

.welcome-message {
    text-align: center;
    padding: 2rem 0;
}

.welcome-message h4 {
    color: #1EA38B;
    margin-bottom: 1rem;
}

.welcome-message ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
    text-align: left;
}

.welcome-message li {
    padding: 0.5rem 0;
    color: #6B7280;
}

.message {
    margin-bottom: 1.5rem;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.user-message .message-content {
    display: flex;
    justify-content: flex-end;
}

.user-message .message-text {
    background: #1EA38B;
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 18px 18px 4px 18px;
    max-width: 70%;
}

.ai-message .message-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.ai-avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #1EA38B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.25rem;
}

.ai-message .message-text {
    background: white;
    color: #1f2937;
    padding: 0.75rem 1rem;
    border-radius: 18px 18px 18px 4px;
    max-width: 70%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.message-time {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 0.25rem;
    padding: 0 0.5rem;
}

.typing-indicator {
    display: flex;
    gap: 0.25rem;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 18px 18px 18px 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #1EA38B;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

.ai-suggestions {
    padding: 1rem 1.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    background: white;
    border-top: 1px solid #e5e7eb;
}

.suggestion-button {
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.875rem;
    color: #374151;
    transition: all 0.2s;
}

.suggestion-button:hover {
    background: #1EA38B;
    color: white;
    transform: translateY(-2px);
}

.ai-input-form {
    display: flex;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: white;
    border-top: 1px solid #e5e7eb;
}

.ai-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    outline: none;
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.ai-input:focus {
    border-color: #1EA38B;
}

.ai-send-button {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    color: white;
    border: none;
    border-radius: 24px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.ai-send-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 163, 139, 0.3);
}

.ai-send-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
    // Scroll automatique vers le bas
    document.addEventListener('livewire:init', () => {
        Livewire.on('scroll-to-bottom', () => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
</script>
@endpush
```

### 9. Controller

```php
<?php
// app/Http/Controllers/AIAssistantController.php

namespace App\Http\Controllers;

use App\Services\AIAssistantService;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    protected $aiService;

    public function __construct(AIAssistantService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * API endpoint pour le chat
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $response = $this->aiService->chat(auth()->user(), $request->message);
            
            return response()->json([
                'success' => true,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    /**
     * Recommandations de cours
     */
    public function recommendCourses(Request $request)
    {
        $criteria = $request->only(['level', 'category', 'budget']);
        
        $courses = $this->aiService->recommendCourses(auth()->user(), $criteria);
        
        return response()->json([
            'success' => true,
            'courses' => $courses,
        ]);
    }

    /**
     * Générer un parcours d'apprentissage
     */
    public function generateLearningPath(Request $request)
    {
        $request->validate([
            'goal' => 'required|string|max:500',
        ]);

        try {
            $path = $this->aiService->generateLearningPath(auth()->user(), $request->goal);
            
            return response()->json([
                'success' => true,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Impossible de générer le parcours.',
            ], 500);
        }
    }
}
```

### 10. Configuration

```php
// config/ai.php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider
    |--------------------------------------------------------------------------
    |
    | Choisissez votre provider IA :
    | - ollama : Gratuit, local, pas de limite
    | - groq : Gratuit, cloud, ultra rapide
    | - huggingface : Gratuit, cloud, 1000 req/mois
    |
    */
    'provider' => env('AI_PROVIDER', 'ollama'),

    /*
    |--------------------------------------------------------------------------
    | Ollama Configuration
    |--------------------------------------------------------------------------
    */
    'ollama_url' => env('OLLAMA_URL', 'http://localhost:11434'),
    'ollama_model' => env('OLLAMA_MODEL', 'llama2'),

    /*
    |--------------------------------------------------------------------------
    | Groq Configuration
    |--------------------------------------------------------------------------
    */
    'groq_api_key' => env('GROQ_API_KEY'),
    'groq_model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),

    /*
    |--------------------------------------------------------------------------
    | Hugging Face Configuration
    |--------------------------------------------------------------------------
    */
    'huggingface_api_key' => env('HUGGINGFACE_API_KEY'),
    'huggingface_model' => env('HUGGINGFACE_MODEL', 'mistralai/Mistral-7B-Instruct-v0.1'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache_enabled' => env('AI_CACHE_ENABLED', true),
    'cache_ttl' => env('AI_CACHE_TTL', 3600),
];
```

### 11. Routes

```php
// routes/web.php

// Ajoutez juste cette ligne pour l'API (optionnel)
Route::middleware('auth')->prefix('api/ai-assistant')->group(function () {
    Route::post('/chat', [App\Http\Controllers\AIAssistantController::class, 'chat']);
    Route::get('/recommend-courses', [App\Http\Controllers\AIAssistantController::class, 'recommendCourses']);
    Route::post('/learning-path', [App\Http\Controllers\AIAssistantController::class, 'generateLearningPath']);
});
```

### 12. Intégration dans les Pages

```blade
{{-- Ajoutez dans votre layout principal --}}
{{-- resources/views/layouts/app.blade.php --}}

@livewire('ai-assistant-chat')
```

---

## 🔧 Installation Complète

### Étape 1 : Installer Ollama

```bash
# Sur Ubuntu/Debian
curl -fsSL https://ollama.ai/install.sh | sh

# Démarrer le service
sudo systemctl start ollama
sudo systemctl enable ollama

# Télécharger un modèle
ollama pull llama2

# Tester
ollama run llama2 "Bonjour"
```

### Étape 2 : Migrations

```bash
php artisan migrate
```

### Étape 3 : Configuration

```bash
# .env
AI_PROVIDER=ollama
OLLAMA_URL=http://localhost:11434
OLLAMA_MODEL=llama2
```

### Étape 4 : Publier la Configuration

```bash
php artisan config:publish ai
```

### Étape 5 : Tester

```bash
php artisan tinker

$service = app(\App\Services\OllamaService::class);
$service->isAvailable(); // Devrait retourner true
```

---

## 🎯 Utilisation

### Dans n'importe quelle page

```blade
{{-- Le composant s'affiche automatiquement en bas à droite --}}
@livewire('ai-assistant-chat')
```

### Exemples d'interactions

```
👤 "Je veux apprendre Laravel"
🤖 "Excellent choix ! Laravel est un framework PHP moderne..."

👤 "Comment créer un cours ?"
🤖 "Pour créer un cours sur AfriCode, voici les étapes..."

👤 "Je veux devenir développeur full-stack"
🤖 "Super ! Voici un parcours personnalisé pour vous..."
```

---

## 💰 Coûts

### Ollama (Recommandé)
- **$0/mois** - 100% gratuit
- Utilise votre serveur
- Pas de limite

### Groq (Alternative)
- **$0/mois** - 30 requêtes/minute
- Cloud, ultra rapide

### Hugging Face (Alternative)
- **$0/mois** - 1000 requêtes/mois
- Cloud, simple

---

## 🚀 Avantages

1. ✅ **100% Laravel** - Pas de React/Vue
2. ✅ **Gratuit** - Ollama est open-source
3. ✅ **Non-invasif** - Aucun fichier existant modifié
4. ✅ **Léger** - Pas de dépendances lourdes
5. ✅ **Performant** - Cache intégré
6. ✅ **Confidentialité** - Données restent chez vous (Ollama)
7. ✅ **Facile à maintenir** - Code simple et clair

---

## 🎉 Conclusion

Vous avez maintenant une **IA Assistant 100% gratuite** qui s'intègre parfaitement à votre projet Laravel existant sans rien casser !

### Prochaines Étapes

1. ✅ Installer Ollama
2. ✅ Exécuter les migrations
3. ✅ Ajouter le composant Livewire
4. ✅ Tester avec vos utilisateurs
5. ✅ Itérer et améliorer

**C'est prêt à l'emploi ! 🚀**

