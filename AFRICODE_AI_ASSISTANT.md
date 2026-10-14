# 🤖 AfriCode AI Assistant - Assistant IA Intelligent

## 📋 Table des Matières
1. [Vue d'Ensemble](#vue-densemble)
2. [Objectifs](#objectifs)
3. [Architecture Technique](#architecture-technique)
4. [Fonctionnalités](#fonctionnalités)
5. [Technologies](#technologies)
6. [Implémentation](#implémentation)
7. [Cas d'Usage](#cas-dusage)
8. [Base de Connaissances](#base-de-connaissances)
9. [Intégration](#intégration)
10. [Roadmap](#roadmap)

---

## 🎯 Vue d'Ensemble

### Concept
**AfriCode AI Assistant** est un assistant virtuel intelligent intégré à la plateforme AfriCode, capable de :
- ✅ Répondre aux questions des utilisateurs
- ✅ Faire des suggestions personnalisées
- ✅ Guider les formateurs et apprenants
- ✅ Recommander des cours
- ✅ Aider à la création de contenu
- ✅ Fournir du support 24/7

### Philosophie
L'IA reste **100% dans le contexte d'AfriCode** :
- 🎓 Focus sur l'éducation en ligne
- 📚 Connaissance de tous les cours de la plateforme
- 👨‍🏫 Aide aux formateurs pour créer du contenu
- 👨‍🎓 Guide les apprenants dans leur parcours
- 🔒 Ne sort jamais du contexte éducatif

---

## 🎯 Objectifs

### Pour les Apprenants
1. **Trouver le bon cours** : Recommandations personnalisées
2. **Résoudre des problèmes** : Aide technique et pédagogique
3. **Progresser** : Conseils pour améliorer l'apprentissage
4. **Répondre aux questions** : FAQ intelligente

### Pour les Formateurs
1. **Créer du contenu** : Suggestions pour structurer les cours
2. **Optimiser les cours** : Recommandations d'amélioration
3. **Engager les étudiants** : Conseils pédagogiques
4. **Analyser les performances** : Insights basés sur les données

### Pour la Plateforme
1. **Réduire le support** : Automatisation des questions courantes
2. **Améliorer l'engagement** : Interactions 24/7
3. **Personnalisation** : Expérience adaptée à chaque utilisateur
4. **Croissance** : Augmentation des conversions

---

## 🏗️ Architecture Technique

### Architecture Globale

```
┌─────────────────────────────────────────────────────────┐
│                    Frontend (React/Vue)                 │
│              Interface Chat en temps réel               │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              API Gateway (Laravel)                      │
│         Authentification & Rate Limiting                │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              AI Service Layer                           │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Intent Recognition (NLP)                        │  │
│  │  - Comprendre l'intention de l'utilisateur       │  │
│  └──────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Context Manager                                 │  │
│  │  - Gérer le contexte de la conversation          │  │
│  └──────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Knowledge Base                                  │  │
│  │  - Base de connaissances AfriCode                │  │
│  └──────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Response Generator                              │  │
│  │  - Générer des réponses contextuelles            │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              LLM Backend                                │
│  ┌──────────────────────────────────────────────────┐  │
│  │  OpenAI GPT-4 / Claude / Llama                  │  │
│  │  - Modèle de langage principal                   │  │
│  └──────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Embeddings (Vector Database)                    │  │
│  │  - Recherche sémantique des cours                │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────┐
│              Database Layer                             │
│  - PostgreSQL (conversations, contexte)                │
│  - Redis (cache, sessions)                             │
│  - Pinecone/Weaviate (vector database)                 │
└─────────────────────────────────────────────────────────┘
```

### Composants Principaux

#### 1. Frontend Chat Interface
```javascript
// Interface de chat moderne et responsive
- Design moderne inspiré de ChatGPT
- Messages en temps réel
- Suggestions de questions
- Typing indicators
- Markdown support
- Code highlighting
- Emojis et réactions
```

#### 2. API Gateway (Laravel)
```php
// Gestion des requêtes
- Authentification utilisateur
- Rate limiting (éviter l'abus)
- Validation des messages
- Logging des conversations
- Analytics
```

#### 3. AI Service Layer
```php
// Logique métier IA
- Intent Recognition (NLP)
- Context Management
- Knowledge Base Retrieval
- Response Generation
- Sentiment Analysis
```

#### 4. LLM Backend
```
// Modèles de langage
- OpenAI GPT-4 (ou Claude, Llama)
- Fine-tuning sur données AfriCode
- Embeddings pour recherche sémantique
- Vector database pour RAG (Retrieval Augmented Generation)
```

---

## 🚀 Fonctionnalités

### 1. 💬 Chat Intelligent

#### Fonctionnalités de base
- **Conversation naturelle** : Dialogue fluide et naturel
- **Contexte** : Mémorise le contexte de la conversation
- **Historique** : Accès à l'historique des conversations
- **Multi-langues** : Support du français, anglais, etc.
- **Suggestions** : Suggestions de questions fréquentes

#### Exemple d'interaction
```
👤 Utilisateur : Je veux apprendre Laravel
🤖 AfriCode AI : Excellent choix ! Laravel est un framework PHP moderne.

J'ai trouvé 3 cours qui pourraient vous intéresser :

1. 🎓 "Laravel pour Débutants" (Gratuit)
   - 15 leçons, 5h de contenu
   - Niveau : Débutant
   - Note : ⭐ 4.8/5

2. 🎓 "Laravel Avancé - APIs et Tests" (15,000 FCFA)
   - 25 leçons, 12h de contenu
   - Niveau : Avancé
   - Note : ⭐ 4.9/5

3. 🎓 "Laravel Full-Stack" (25,000 FCFA)
   - 40 leçons, 20h de contenu
   - Niveau : Tous niveaux
   - Note : ⭐ 4.7/5

Quel cours vous intéresse le plus ? Je peux vous donner plus de détails.
```

### 2. 🎯 Recommandations Personnalisées

#### Pour les Apprenants
```javascript
// Analyse du profil
- Niveau actuel
- Objectifs d'apprentissage
- Cours déjà suivis
- Préférences
- Budget
- Temps disponible

// Recommandations
- Cours adaptés au niveau
- Parcours d'apprentissage
- Prérequis nécessaires
- Ressources complémentaires
```

#### Pour les Formateurs
```javascript
// Analyse du contenu
- Sujets populaires
- Concurrence
- Prix du marché
- Tendances

// Recommandations
- Sujets à créer
- Prix optimal
- Structure du cours
- Marketing
```

### 3. 📚 Aide à la Création de Contenu

#### Pour les Formateurs
```
👤 Formateur : Je veux créer un cours sur React
🤖 AfriCode AI : Parfait ! Voici un plan structuré pour votre cours :

📋 Structure Recommandée :

Module 1 : Introduction (2h)
  - Leçon 1 : Qu'est-ce que React ?
  - Leçon 2 : Installation et configuration
  - Quiz 1 : Bases de React

Module 2 : Composants (4h)
  - Leçon 3 : Composants fonctionnels
  - Leçon 4 : Props et State
  - Leçon 5 : Lifecycle
  - Quiz 2 : Composants

Module 3 : Hooks (3h)
  - Leçon 6 : useState et useEffect
  - Leçon 7 : Custom Hooks
  - Quiz 3 : Hooks

Module 4 : Projet Pratique (6h)
  - Leçon 8 : Créer une Todo App
  - Leçon 9 : Intégration API
  - Projet Final

💡 Conseils :
- Ajoutez des exemples pratiques
- Créez des quiz après chaque module
- Proposez un projet final
- Prix suggéré : 20,000 FCFA

Voulez-vous que je génère le contenu détaillé d'un module ?
```

### 4. 🎓 Guide d'Apprentissage

#### Parcours Personnalisé
```
👤 Apprenant : Je suis débutant en développement web
🤖 AfriCode AI : Super ! Voici votre parcours recommandé :

🎯 Parcours Développeur Web Débutant

Étape 1 : Fondamentaux (2 mois)
  ✅ HTML & CSS - "Les Bases du Web" (Gratuit)
  ✅ JavaScript - "JS pour Débutants" (10,000 FCFA)

Étape 2 : Frontend (3 mois)
  ✅ React - "React pour Débutants" (15,000 FCFA)
  ✅ Projet : Créer un portfolio

Étape 3 : Backend (4 mois)
  ✅ Node.js - "Backend avec Node.js" (20,000 FCFA)
  ✅ Base de données - "MySQL pour Débutants" (12,000 FCFA)

Étape 4 : Full-Stack (3 mois)
  ✅ Projet : Application complète
  ✅ Déploiement et DevOps

📊 Objectif : Être prêt pour un poste junior en 12 mois
💰 Investissement total : ~70,000 FCFA

Voulez-vous commencer par l'Étape 1 ?
```

### 5. 🔍 Recherche Intelligente

#### Recherche Sémantique
```javascript
// Au lieu de recherche par mots-clés
"apprendre à coder"

// Recherche sémantique comprend :
- Programmation
- Développement
- Coding
- Software engineering
- Etc.

// Résultats contextuels
- Cours de programmation
- Tutoriels
- Ressources
- Projets pratiques
```

### 6. 🆘 Support Technique

#### Résolution de Problèmes
```
👤 Utilisateur : Je n'arrive pas à accéder à mon cours
🤖 AfriCode AI : Je vais vous aider à résoudre ce problème.

🔍 Diagnostic :
1. Avez-vous complété le paiement ?
2. Avez-vous reçu l'email de confirmation ?
3. Le cours est-il toujours actif ?

💡 Solutions possibles :
- Vérifiez votre email de confirmation
- Consultez "Mes Cours" dans votre profil
- Contactez le support si le problème persiste

Voulez-vous que je vérifie votre statut d'inscription ?
```

### 7. 📊 Insights et Analytics

#### Pour les Formateurs
```
🤖 AfriCode AI : Voici l'analyse de votre cours "Laravel Avancé" :

📈 Performance :
- Taux de complétion : 68% (excellent !)
- Temps moyen : 14h (objectif 12h)
- Note moyenne : 4.7/5

💡 Recommandations :
1. Module 3 a un taux d'abandon de 35%
   → Ajoutez plus d'exemples pratiques
   
2. Quiz 2 est trop difficile (45% de réussite)
   → Simplifiez les questions
   
3. Les étudiants demandent plus de projets
   → Ajoutez un projet intermédiaire

📊 Comparaison avec le marché :
- Votre prix est 15% en dessous de la moyenne
- Vous pourriez augmenter à 22,000 FCFA

Voulez-vous que je génère un plan d'amélioration ?
```

---

## 🛠️ Technologies

### Stack Technique

#### Backend
```yaml
Framework: Laravel 12
Language: PHP 8.2+
Database: PostgreSQL 15+
Cache: Redis 7+
Queue: Laravel Horizon
```

#### AI/ML
```yaml
LLM: OpenAI GPT-4 / Anthropic Claude / Llama 2
Embeddings: OpenAI text-embedding-3-large
Vector DB: Pinecone / Weaviate / Qdrant
NLP: spaCy / NLTK
```

#### Frontend
```yaml
Framework: React / Vue.js
UI: TailwindCSS + Headless UI
Real-time: Laravel Echo + Socket.io
Markdown: react-markdown / marked
```

#### Infrastructure
```yaml
Hosting: AWS / DigitalOcean / Vultr
CDN: Cloudflare
Monitoring: Sentry
Analytics: Mixpanel
```

---

## 💻 Implémentation

### 1. Base de Données

#### Table: ai_conversations
```sql
CREATE TABLE ai_conversations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED,
    session_id VARCHAR(255),
    messages JSON,
    context JSON,
    intent VARCHAR(100),
    sentiment VARCHAR(20),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### Table: ai_knowledge_base
```sql
CREATE TABLE ai_knowledge_base (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    category VARCHAR(100),
    embedding VECTOR(1536),
    metadata JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Table: ai_suggestions
```sql
CREATE TABLE ai_suggestions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED,
    type ENUM('course', 'content', 'path'),
    suggestion JSON,
    confidence DECIMAL(3,2),
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### 2. Modèles Laravel

#### AIConversation Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIConversation extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'messages',
        'context',
        'intent',
        'sentiment',
    ];

    protected $casts = [
        'messages' => 'array',
        'context' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addMessage(string $role, string $content)
    {
        $messages = $this->messages ?? [];
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now(),
        ];
        
        $this->update(['messages' => $messages]);
    }

    public function getContext(): array
    {
        return $this->context ?? [];
    }

    public function updateContext(array $context)
    {
        $this->update(['context' => $context]);
    }
}
```

#### AIAssistant Service
```php
<?php

namespace App\Services;

use App\Models\AIConversation;
use App\Models\Course;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;

class AIAssistantService
{
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
        $prompt = "Analyse l'intention de ce message et réponds avec UN SEUL mot parmi :
        - course_search : Recherche de cours
        - course_recommendation : Recommandation de cours
        - learning_path : Parcours d'apprentissage
        - content_creation : Création de contenu
        - technical_support : Support technique
        - general_question : Question générale

        Message: {$message}
        
        Intention:";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un analyseur d\'intentions.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        return trim($response->choices[0]->message->content);
    }

    private function buildContext(User $user, string $intent, string $message): array
    {
        $context = [
            'user_id' => $user->id,
            'role' => $user->role,
            'intent' => $intent,
        ];

        // Ajouter des informations spécifiques selon l'intention
        if ($intent === 'course_search' || $intent === 'course_recommendation') {
            $context['user_courses'] = $user->enrollments()
                ->with('course')
                ->get()
                ->pluck('course.title')
                ->toArray();
            
            $context['user_level'] = $this->determineUserLevel($user);
        }

        if ($intent === 'learning_path') {
            $context['user_goals'] = $user->profile->goals ?? [];
            $context['user_interests'] = $user->profile->interests ?? [];
        }

        if ($intent === 'content_creation' && $user->role === 'formateur') {
            $context['formateur_courses'] = $user->courses()->pluck('title')->toArray();
            $context['formateur_expertise'] = $user->profile->expertise ?? [];
        }

        return $context;
    }

    private function generateResponse(AIConversation $conversation, array $context): string
    {
        // Récupérer les messages précédents
        $messages = $conversation->messages;
        
        // Construire le système prompt
        $systemPrompt = $this->buildSystemPrompt($context);
        
        // Construire les messages pour l'API
        $apiMessages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];
        
        // Ajouter les messages précédents (limite à 10 derniers)
        foreach (array_slice($messages, -10) as $msg) {
            $apiMessages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }
        
        // Appeler l'API OpenAI
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => $apiMessages,
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        return $response->choices[0]->message->content;
    }

    private function buildSystemPrompt(array $context): string
    {
        $prompt = "Tu es AfriCode AI, l'assistant intelligent de la plateforme d'apprentissage AfriCode.
        
Règles importantes :
1. Tu restes TOUJOURS dans le contexte d'AfriCode et de l'éducation en ligne
2. Tu ne réponds QUE sur les sujets liés à l'apprentissage, aux cours, à la formation
3. Si on te pose une question hors contexte, tu rediriges vers le support AfriCode
4. Tu es bienveillant, encourageant et pédagogique
5. Tu utilises des emojis pour rendre tes réponses plus engageantes

Contexte utilisateur :
- Rôle : {$context['role']}
- Intention : {$context['intent']}
";

        if (isset($context['user_courses'])) {
            $prompt .= "\nCours suivis : " . implode(', ', $context['user_courses']);
        }

        if (isset($context['user_level'])) {
            $prompt .= "\nNiveau : {$context['user_level']}";
        }

        $prompt .= "\n\nBase de connaissances AfriCode :";
        $prompt .= "\n- Cours disponibles dans toutes les catégories";
        $prompt .= "\n- Système de badges et gamification";
        $prompt .= "\n- Certificats PDF vérifiables";
        $prompt .= "\n- Forum de discussion";
        $prompt .= "\n- Support 24/7";

        return $prompt;
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

    public function generateLearningPath(User $user, string $goal): array
    {
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

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert en pédagogie et parcours d\'apprentissage.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
        ]);

        return json_decode($response->choices[0]->message->content, true);
    }
}
```

### 3. Contrôleur API

#### AIAssistantController
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Chat avec l'assistant IA
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        
        try {
            $response = $this->aiService->chat($user, $request->message);
            
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
     * Obtenir des recommandations de cours
     */
    public function recommendCourses(Request $request)
    {
        $user = auth()->user();
        
        $criteria = $request->only(['level', 'category', 'budget']);
        
        $courses = $this->aiService->recommendCourses($user, $criteria);
        
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

        $user = auth()->user();
        
        try {
            $path = $this->aiService->generateLearningPath($user, $request->goal);
            
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

    /**
     * Obtenir l'historique des conversations
     */
    public function getHistory(Request $request)
    {
        $user = auth()->user();
        
        $conversations = $user->aiConversations()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Obtenir des suggestions de questions
     */
    public function getSuggestions()
    {
        $suggestions = [
            "Je veux apprendre Laravel",
            "Quels cours me recommandez-vous ?",
            "Comment créer un cours ?",
            "Qu'est-ce que la gamification ?",
            "Comment obtenir un certificat ?",
            "Quels sont les cours gratuits ?",
            "Comment progresser rapidement ?",
            "Qu'est-ce que le forum ?",
        ];

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }
}
```

### 4. Routes API

```php
// routes/api.php

Route::prefix('ai-assistant')->middleware('auth:sanctum')->group(function () {
    Route::post('/chat', [AIAssistantController::class, 'chat']);
    Route::get('/recommend-courses', [AIAssistantController::class, 'recommendCourses']);
    Route::post('/learning-path', [AIAssistantController::class, 'generateLearningPath']);
    Route::get('/history', [AIAssistantController::class, 'getHistory']);
    Route::get('/suggestions', [AIAssistantController::class, 'getSuggestions']);
});
```

### 5. Frontend React

#### AIAssistant Component
```jsx
import React, { useState, useRef, useEffect } from 'react';
import axios from 'axios';

const AIAssistant = () => {
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState('');
    const [loading, setLoading] = useState(false);
    const [suggestions, setSuggestions] = useState([]);
    const messagesEndRef = useRef(null);

    useEffect(() => {
        fetchSuggestions();
        scrollToBottom();
    }, [messages]);

    const fetchSuggestions = async () => {
        try {
            const response = await axios.get('/api/ai-assistant/suggestions');
            setSuggestions(response.data.suggestions);
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    };

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    const sendMessage = async (message = null) => {
        const messageText = message || input;
        if (!messageText.trim()) return;

        // Ajouter le message de l'utilisateur
        const userMessage = {
            role: 'user',
            content: messageText,
            timestamp: new Date(),
        };
        setMessages(prev => [...prev, userMessage]);
        setInput('');
        setLoading(true);

        try {
            const response = await axios.post('/api/ai-assistant/chat', {
                message: messageText,
            });

            // Ajouter la réponse de l'IA
            const aiMessage = {
                role: 'assistant',
                content: response.data.response,
                timestamp: new Date(),
            };
            setMessages(prev => [...prev, aiMessage]);
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
        } finally {
            setLoading(false);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        sendMessage();
    };

    const handleSuggestionClick = (suggestion) => {
        sendMessage(suggestion);
    };

    return (
        <div className="ai-assistant-container">
            <div className="ai-assistant-header">
                <div className="ai-avatar">
                    <span>🤖</span>
                </div>
                <div className="ai-info">
                    <h3>AfriCode AI Assistant</h3>
                    <p>Comment puis-je vous aider ?</p>
                </div>
            </div>

            <div className="ai-messages">
                {messages.length === 0 && (
                    <div className="welcome-message">
                        <h4>👋 Bienvenue !</h4>
                        <p>Je suis AfriCode AI, votre assistant intelligent.</p>
                        <p>Je peux vous aider à :</p>
                        <ul>
                            <li>🎓 Trouver des cours adaptés</li>
                            <li>📚 Créer un parcours d'apprentissage</li>
                            <li>💡 Obtenir des recommandations</li>
                            <li>🆘 Résoudre vos problèmes</li>
                        </ul>
                        <p>Essayez une de ces questions :</p>
                    </div>
                )}

                {messages.map((message, index) => (
                    <div
                        key={index}
                        className={`message ${message.role === 'user' ? 'user-message' : 'ai-message'}`}
                    >
                        <div className="message-content">
                            {message.role === 'assistant' && (
                                <div className="ai-avatar-small">🤖</div>
                            )}
                            <div className="message-text">
                                {message.content}
                            </div>
                        </div>
                        <div className="message-time">
                            {new Date(message.timestamp).toLocaleTimeString()}
                        </div>
                    </div>
                ))}

                {loading && (
                    <div className="message ai-message">
                        <div className="message-content">
                            <div className="ai-avatar-small">🤖</div>
                            <div className="typing-indicator">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                )}

                <div ref={messagesEndRef} />
            </div>

            {messages.length === 0 && suggestions.length > 0 && (
                <div className="suggestions">
                    {suggestions.map((suggestion, index) => (
                        <button
                            key={index}
                            className="suggestion-button"
                            onClick={() => handleSuggestionClick(suggestion)}
                        >
                            {suggestion}
                        </button>
                    ))}
                </div>
            )}

            <form className="ai-input-form" onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    placeholder="Posez votre question..."
                    disabled={loading}
                    className="ai-input"
                />
                <button
                    type="submit"
                    disabled={loading || !input.trim()}
                    className="ai-send-button"
                >
                    <span>Envoyer</span>
                    <span>✈️</span>
                </button>
            </form>
        </div>
    );
};

export default AIAssistant;
```

#### CSS Styles
```css
.ai-assistant-container {
    display: flex;
    flex-direction: column;
    height: 600px;
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.ai-assistant-header {
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

.ai-info h3 {
    margin: 0;
    font-size: 1.25rem;
}

.ai-info p {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 0.875rem;
}

.ai-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    background: #f9fafb;
}

.welcome-message {
    text-align: center;
    padding: 2rem;
}

.welcome-message h4 {
    color: #1EA38B;
    margin-bottom: 1rem;
}

.welcome-message ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
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

.suggestions {
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
```

---

## 📊 Cas d'Usage

### Cas 1 : Apprenant cherche un cours
```
👤 "Je veux apprendre React"
🤖 Analyse l'intention → course_search
🤖 Récupère le niveau de l'utilisateur → débutant
🤖 Recherche les cours React pour débutants
🤖 Génère une réponse personnalisée avec 3 cours recommandés
```

### Cas 2 : Formateur veut créer un cours
```
👤 "Comment créer un cours sur Python ?"
🤖 Analyse l'intention → content_creation
🤖 Vérifie que l'utilisateur est formateur
🤖 Génère un plan structuré avec modules et leçons
🤖 Propose des conseils pédagogiques
```

### Cas 3 : Apprenant veut un parcours
```
👤 "Je veux devenir développeur full-stack"
🤖 Analyse l'intention → learning_path
🤖 Évalue le niveau actuel → débutant
🤖 Génère un parcours complet sur 12 mois
🤖 Inclut les cours, durée, budget
```

---

## 🗄️ Base de Connaissances

### Contenu à Indexer

#### 1. Documentation AfriCode
```json
{
  "title": "Guide d'utilisation AfriCode",
  "content": "AfriCode est une plateforme d'apprentissage en ligne...",
  "category": "documentation",
  "url": "https://africode.com/docs"
}
```

#### 2. Cours Disponibles
```json
{
  "title": "Laravel pour Débutants",
  "content": "Apprenez Laravel de A à Z...",
  "category": "course",
  "instructor": "Jean Dupont",
  "level": "débutant",
  "price": 15000,
  "duration": "12h"
}
```

#### 3. FAQ
```json
{
  "question": "Comment obtenir un certificat ?",
  "answer": "Pour obtenir un certificat, vous devez...",
  "category": "faq"
}
```

#### 4. Conseils Pédagogiques
```json
{
  "title": "Comment structurer un cours",
  "content": "Un bon cours doit avoir...",
  "category": "pedagogy"
}
```

### Indexation avec Embeddings

```php
<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Course;
use App\Models\AIConversation;

class KnowledgeBaseService
{
    public function indexCourse(Course $course)
    {
        // Créer le texte à indexer
        $text = $this->buildCourseText($course);
        
        // Générer l'embedding
        $embedding = $this->generateEmbedding($text);
        
        // Stocker dans la base de connaissances
        AIConversation::create([
            'title' => $course->title,
            'content' => $text,
            'category' => 'course',
            'embedding' => $embedding,
            'metadata' => [
                'course_id' => $course->id,
                'instructor' => $course->formateur->name,
                'level' => $course->level,
                'price' => $course->price,
            ],
        ]);
    }

    private function buildCourseText(Course $course): string
    {
        $text = "Cours : {$course->title}\n";
        $text .= "Description : {$course->description}\n";
        $text .= "Niveau : {$course->level}\n";
        $text .= "Prix : {$course->price} FCFA\n";
        $text .= "Formateur : {$course->formateur->name}\n";
        
        if ($course->modules) {
            $text .= "Modules :\n";
            foreach ($course->modules as $module) {
                $text .= "- {$module->title}\n";
            }
        }
        
        return $text;
    }

    private function generateEmbedding(string $text): array
    {
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-large',
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }

    public function searchSimilar(string $query, int $limit = 5): array
    {
        // Générer l'embedding de la requête
        $queryEmbedding = $this->generateEmbedding($query);
        
        // Rechercher les documents similaires (utilisation de PostgreSQL avec pgvector)
        $results = DB::select("
            SELECT 
                title,
                content,
                category,
                metadata,
                1 - (embedding <=> ?) as similarity
            FROM ai_knowledge_base
            WHERE 1 - (embedding <=> ?) > 0.7
            ORDER BY similarity DESC
            LIMIT ?
        ", [$queryEmbedding, $queryEmbedding, $limit]);
        
        return $results;
    }
}
```

---

## 🔧 Intégration

### 1. Installation des Dépendances

```bash
# Composer
composer require openai-php/laravel
composer require pinecone/pinecone-php

# NPM
npm install axios react-markdown
```

### 2. Configuration

```env
# .env
OPENAI_API_KEY=sk-...
OPENAI_ORGANIZATION=org-...
PINECONE_API_KEY=...
PINECONE_ENVIRONMENT=...
```

### 3. Migrations

```bash
php artisan make:migration create_ai_conversations_table
php artisan make:migration create_ai_knowledge_base_table
php artisan make:migration create_ai_suggestions_table
php artisan migrate
```

### 4. Routes

```php
// routes/web.php
Route::get('/ai-assistant', [AIAssistantController::class, 'index']);

// routes/api.php
Route::prefix('ai-assistant')->middleware('auth:sanctum')->group(function () {
    Route::post('/chat', [AIAssistantController::class, 'chat']);
    // ... autres routes
});
```

### 5. Vue Blade

```blade
{{-- resources/views/ai-assistant/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div id="ai-assistant-app"></div>

@vite(['resources/js/ai-assistant.js'])
@endsection
```

---

## 🚀 Roadmap

### Phase 1 : MVP (2-3 mois)
- ✅ Chat basique avec GPT-4
- ✅ Recommandations de cours
- ✅ Interface utilisateur
- ✅ Authentification
- ✅ Historique des conversations

### Phase 2 : Amélioration (3-4 mois)
- 🔄 Base de connaissances AfriCode
- 🔄 Recherche sémantique (RAG)
- 🔄 Génération de parcours d'apprentissage
- 🔄 Aide à la création de contenu
- 🔄 Analytics et insights

### Phase 3 : Avancé (4-6 mois)
- 📅 Fine-tuning du modèle
- 📅 Multi-langues
- 📅 Voice interface
- 📅 Mobile app
- 📅 Intégration avec le forum

### Phase 4 : Intelligence (6-12 mois)
- 🚀 Prédictions de succès
- 🚀 Adaptation automatique
- 🚀 Recommandations temps réel
- 🚀 Analyse de sentiment
- 🚀 Prévention d'abandon

---

## 💰 Coûts Estimés

### Infrastructure
```
OpenAI GPT-4 API : ~$0.03 par 1K tokens
→ ~$500-1000/mois pour 10K utilisateurs

Vector Database (Pinecone) : ~$70/mois
Hosting : ~$100/mois
Total : ~$670-1170/mois
```

### ROI
```
Réduction support : -30% des tickets
→ Économie : ~$2000/mois

Augmentation conversions : +15%
→ Revenus supplémentaires : ~$5000/mois

ROI positif dès le 2ème mois
```

---

## 🎯 Conclusion

**AfriCode AI Assistant** est une fonctionnalité révolutionnaire qui va :

1. **Transformer l'expérience utilisateur** : Assistance 24/7, personnalisée
2. **Augmenter les conversions** : Recommandations intelligentes
3. **Réduire les coûts** : Automatisation du support
4. **Améliorer la rétention** : Guidance continue
5. **Différencier AfriCode** : Innovation technologique

### Prochaines Étapes

1. ✅ Valider le concept avec les utilisateurs
2. ✅ Développer le MVP
3. ✅ Tester avec un groupe beta
4. ✅ Itérer et améliorer
5. ✅ Déployer en production

---

**AfriCode AI Assistant** - L'IA qui vous aide à apprendre et enseigner 🚀

*Version 1.0 - Janvier 2024*

