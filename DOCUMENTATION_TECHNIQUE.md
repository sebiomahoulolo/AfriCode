# 📚 AfriCode - Documentation Technique

## 📋 Table des Matières
1. [Architecture Technique](#architecture-technique)
2. [Base de Données](#base-de-données)
3. [API et Contrôleurs](#api-et-contrôleurs)
4. [Modèles et Relations](#modèles-et-relations)
5. [Services et Logique Métier](#services-et-logique-métier)
6. [Frontend et Assets](#frontend-et-assets)
7. [Sécurité](#sécurité)
8. [Performance](#performance)
9. [Tests](#tests)
10. [Déploiement](#déploiement)

---

## 🏗️ Architecture Technique

### Stack Technologique

#### Backend
```yaml
Framework: Laravel 12.0
PHP: 8.2+
Database: MySQL 8.0+ / PostgreSQL 13+
Cache: Redis / Memcached
Queue: Redis / Database
```

#### Frontend
```yaml
Templates: Blade
CSS Framework: TailwindCSS 3.x + Bootstrap 5.x
JavaScript: ES6+ (Vanilla)
Build Tool: Vite
Animations: AOS (Animate On Scroll)
```

#### Services Externes
```yaml
Paiements: Stripe, PayPal
Emails: PHPMailer
PDF: DomPDF
Auth Social: Laravel Socialite
Audit: Laravel Auditing
```

### Structure du Projet

```
AfriCode/
├── app/
│   ├── Console/
│   │   ├── Commands/              # 23 commandes Artisan
│   │   └── Kernel.php             # Planification des tâches
│   │
│   ├── Http/
│   │   ├── Controllers/           # 56+ contrôleurs
│   │   │   ├── Auth/             # Authentification
│   │   │   ├── Api/              # API REST
│   │   │   ├── CourseController.php
│   │   │   ├── QuizController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ...
│   │   ├── Middleware/           # 10 middlewares
│   │   │   ├── Authenticate.php
│   │   │   ├── VerifyCsrfToken.php
│   │   │   └── ...
│   │   ├── Requests/             # Validation
│   │   │   ├── StoreCourseRequest.php
│   │   │   └── ...
│   │   └── Kernel.php            # Middleware global
│   │
│   ├── Livewire/                 # Composants Livewire
│   │   ├── Actions/
│   │   ├── AdvancedSearch.php
│   │   ├── ApprenantDashboard.php
│   │   ├── CoursesFilterSearch.php
│   │   └── CourseShowcase.php
│   │
│   ├── Models/                   # 75+ modèles Eloquent
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Lesson.php
│   │   ├── Quiz.php
│   │   ├── Enrollment.php
│   │   └── ...
│   │
│   ├── Notifications/            # Notifications
│   │   ├── BadgeUnlocked.php
│   │   ├── ProgressionMilestone.php
│   │   └── ...
│   │
│   ├── Policies/                 # Autorisations
│   │   ├── QuestionPolicy.php
│   │   └── ...
│   │
│   ├── Providers/                # Service Providers
│   │   ├── AppServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── ...
│   │
│   └── Services/                 # Services métier
│       ├── PayPalService.php
│       ├── NotificationService.php
│       ├── ContactMailService.php
│       └── ...
│
├── database/
│   ├── factories/                # Factories pour tests
│   │   └── UserFactory.php
│   ├── migrations/               # 82+ migrations
│   │   ├── 2024_01_01_create_users_table.php
│   │   ├── 2024_01_02_create_courses_table.php
│   │   └── ...
│   └── seeders/                  # 23+ seeders
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── ...
│
├── resources/
│   ├── css/                      # Styles CSS
│   │   ├── app.css
│   │   ├── formateur.css
│   │   ├── homepage.css
│   │   ├── components/
│   │   │   ├── cards.css
│   │   │   ├── header.css
│   │   │   ├── sidebar.css
│   │   │   └── ...
│   │   └── layouts/
│   │       └── formateur.css
│   │
│   ├── js/                       # JavaScript
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   ├── homepage.js
│   │   └── components/
│   │       └── formateur.js
│   │
│   └── views/                    # 215+ vues Blade
│       ├── admin/
│       ├── apprenants/
│       ├── formateurs/
│       ├── courses/
│       ├── components/
│       └── layouts/
│
├── routes/                       # Routes
│   ├── web.php                   # Routes web
│   ├── api.php                   # Routes API
│   ├── auth.php                  # Routes auth
│   └── console.php               # Routes console
│
├── tests/                        # Tests
│   ├── Feature/                  # Tests fonctionnels
│   └── Unit/                     # Tests unitaires
│
├── config/                       # Configuration
│   ├── app.php
│   ├── database.php
│   ├── mail.php
│   ├── services.php
│   └── ...
│
└── public/                       # Assets publics
    ├── assets/images/
    ├── js/
    └── storage/
```

---

## 🗄️ Base de Données

### Diagramme ER (Entités Principales)

```
┌─────────────┐
│    User     │
└──────┬──────┘
       │
       ├─────────────────┐
       │                 │
       ▼                 ▼
┌──────────────┐   ┌──────────────┐
│   Formateur  │   │  Apprenant   │
└──────┬───────┘   └──────┬───────┘
       │                  │
       │                  │
       ▼                  ▼
┌──────────────┐   ┌──────────────┐
│    Course    │   │  Enrollment  │
└──────┬───────┘   └──────┬───────┘
       │                  │
       ├──────────┬───────┤
       │          │       │
       ▼          ▼       ▼
┌──────────┐ ┌──────┐ ┌──────┐
│ Module   │ │Lesson│ │ Quiz │
└────┬─────┘ └──────┘ └──┬───┘
     │                   │
     │                   │
     ▼                   ▼
┌──────────┐        ┌──────────┐
│Resource  │        │Question  │
└──────────┘        └──────────┘
```

### Tables Principales

#### Users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'formateur', 'apprenant'),
    profile_image VARCHAR(255),
    email_verified_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Courses
```sql
CREATE TABLE courses (
    id BIGINT UNSIGNED PRIMARY KEY,
    formateur_id BIGINT UNSIGNED,
    category_id BIGINT UNSIGNED,
    title VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    short_description TEXT,
    cover_image_path VARCHAR(255),
    price DECIMAL(10, 2),
    is_free BOOLEAN DEFAULT FALSE,
    is_certifying BOOLEAN DEFAULT FALSE,
    level ENUM('debutant', 'intermediaire', 'avance', 'tous_niveaux'),
    is_promotional BOOLEAN DEFAULT FALSE,
    discount_percentage INT,
    original_price DECIMAL(10, 2),
    promotion_start_date DATE,
    promotion_end_date DATE,
    status ENUM('draft', 'published', 'archived'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (formateur_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

#### Lessons
```sql
CREATE TABLE lessons (
    id BIGINT UNSIGNED PRIMARY KEY,
    module_id BIGINT UNSIGNED,
    title VARCHAR(255),
    content TEXT,
    video_url VARCHAR(255),
    duration INT,
    order_index INT,
    is_free BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id)
);
```

#### Quizzes
```sql
CREATE TABLE quizzes (
    id BIGINT UNSIGNED PRIMARY KEY,
    lesson_id BIGINT UNSIGNED,
    title VARCHAR(255),
    description TEXT,
    time_limit INT,
    pass_score INT,
    max_attempts INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id)
);
```

#### Questions
```sql
CREATE TABLE questions (
    id BIGINT UNSIGNED PRIMARY KEY,
    quiz_id BIGINT UNSIGNED,
    question_text TEXT,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer', 'essay'),
    points INT DEFAULT 1,
    media_url VARCHAR(255),
    explanation TEXT,
    order_index INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);
```

#### Enrollments
```sql
CREATE TABLE enrollments (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    course_id BIGINT UNSIGNED,
    payment_id BIGINT UNSIGNED,
    status ENUM('active', 'completed', 'cancelled'),
    enrolled_at TIMESTAMP,
    completed_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (payment_id) REFERENCES payments(id)
);
```

#### Payments
```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    course_id BIGINT UNSIGNED,
    amount DECIMAL(10, 2),
    currency VARCHAR(3) DEFAULT 'XOF',
    payment_method ENUM('stripe', 'paypal', 'mobile_money'),
    transaction_id VARCHAR(255),
    status ENUM('pending', 'completed', 'failed', 'refunded'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);
```

#### Badges
```sql
CREATE TABLE badges (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    icon VARCHAR(255),
    color VARCHAR(7),
    criteria_type ENUM('course_completion', 'quiz_score', 'forum_participation'),
    criteria_value INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Certifications
```sql
CREATE TABLE certifications (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    course_id BIGINT UNSIGNED,
    certificate_number VARCHAR(255) UNIQUE,
    issued_at TIMESTAMP,
    expires_at TIMESTAMP,
    pdf_path VARCHAR(255),
    qr_code VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);
```

### Relations Entre Tables

```php
// User Model
class User extends Model {
    // Formateur
    public function courses() {
        return $this->hasMany(Course::class, 'formateur_id');
    }
    
    // Apprenant
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    
    public function quizAttempts() {
        return $this->hasMany(QuizAttempt::class);
    }
    
    public function badges() {
        return $this->belongsToMany(Badge::class, 'badge_user');
    }
    
    public function certifications() {
        return $this->hasMany(Certification::class);
    }
}

// Course Model
class Course extends Model {
    public function formateur() {
        return $this->belongsTo(User::class, 'formateur_id');
    }
    
    public function modules() {
        return $this->hasMany(Module::class);
    }
    
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function prerequisites() {
        return $this->belongsToMany(Course::class, 'course_prerequisites', 
            'course_id', 'prerequisite_id');
    }
}

// Module Model
class Module extends Model {
    public function course() {
        return $this->belongsTo(Course::class);
    }
    
    public function lessons() {
        return $this->hasMany(Lesson::class);
    }
}

// Lesson Model
class Lesson extends Model {
    public function module() {
        return $this->belongsTo(Module::class);
    }
    
    public function quiz() {
        return $this->hasOne(Quiz::class);
    }
    
    public function resources() {
        return $this->hasMany(Resource::class);
    }
    
    public function completions() {
        return $this->hasMany(LessonCompletion::class);
    }
}

// Quiz Model
class Quiz extends Model {
    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
    
    public function questions() {
        return $this->hasMany(Question::class);
    }
    
    public function attempts() {
        return $this->hasMany(QuizAttempt::class);
    }
}

// Question Model
class Question extends Model {
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
    
    public function options() {
        return $this->hasMany(QuestionOption::class);
    }
    
    public function correctOption() {
        return $this->hasOne(QuestionOption::class)->where('is_correct', true);
    }
}

// Enrollment Model
class Enrollment extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function course() {
        return $this->belongsTo(Course::class);
    }
    
    public function payment() {
        return $this->belongsTo(Payment::class);
    }
    
    public function progress() {
        return $this->hasOne(UserProgress::class);
    }
}
```

---

## 🔌 API et Contrôleurs

### Routes API

#### Courses API
```php
// routes/api.php

Route::prefix('courses')->group(function () {
    // Public routes
    Route::get('/', [CourseController::class, 'index']);
    Route::get('/{course}', [CourseController::class, 'show']);
    Route::get('/{course}/modules', [CourseController::class, 'modules']);
    
    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [CourseController::class, 'store']);
        Route::put('/{course}', [CourseController::class, 'update']);
        Route::delete('/{course}', [CourseController::class, 'destroy']);
        Route::post('/{course}/enroll', [EnrollmentController::class, 'store']);
    });
});
```

#### Quizzes API
```php
Route::prefix('quizzes')->group(function () {
    Route::get('/{quiz}', [QuizController::class, 'show']);
    Route::get('/{quiz}/questions', [QuizController::class, 'questions']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [QuizController::class, 'store']);
        Route::put('/{quiz}', [QuizController::class, 'update']);
        Route::post('/{quiz}/attempt', [QuizController::class, 'attempt']);
        Route::get('/{quiz}/results', [QuizController::class, 'results']);
    });
});
```

#### Payments API
```php
Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::post('/stripe', [PaymentController::class, 'stripe']);
    Route::post('/paypal', [PaymentController::class, 'paypal']);
    Route::get('/history', [PaymentController::class, 'history']);
});
```

### Contrôleurs Principaux

#### CourseController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['formateur', 'category'])
            ->where('status', 'published');
        
        // Filtres
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }
        
        if ($request->has('price')) {
            if ($request->price === 'free') {
                $query->where('is_free', true);
            } else {
                $query->where('is_free', false);
            }
        }
        
        // Recherche
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $courses = $query->paginate(12);
        
        return response()->json($courses);
    }
    
    public function show(Course $course)
    {
        $course->load([
            'formateur',
            'category',
            'modules.lessons',
            'modules.lessons.quiz',
            'enrollments' => function($query) {
                $query->where('status', 'completed');
            }
        ]);
        
        return response()->json($course);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:debutant,intermediaire,avance,tous_niveaux',
            'is_certifying' => 'boolean',
        ]);
        
        $course = auth()->user()->courses()->create($validated);
        
        return response()->json($course, 201);
    }
    
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'price' => 'sometimes|numeric|min:0',
            'level' => 'sometimes|in:debutant,intermediaire,avance,tous_niveaux',
            'is_certifying' => 'boolean',
            'status' => 'sometimes|in:draft,published,archived',
        ]);
        
        $course->update($validated);
        
        return response()->json($course);
    }
    
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        
        $course->delete();
        
        return response()->json(['message' => 'Course deleted successfully']);
    }
}
```

#### QuizController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function attempt(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required',
        ]);
        
        $user = auth()->user();
        
        // Vérifier le nombre de tentatives
        $attemptsCount = $user->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->count();
            
        if ($quiz->max_attempts > 0 && $attemptsCount >= $quiz->max_attempts) {
            return response()->json([
                'error' => 'Maximum attempts reached'
            ], 403);
        }
        
        // Calculer le score
        $score = $this->calculateScore($quiz, $validated['answers']);
        $percentage = ($score / $quiz->questions->sum('points')) * 100;
        $passed = $percentage >= $quiz->pass_score;
        
        // Enregistrer la tentative
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'max_score' => $quiz->questions->sum('points'),
            'percentage' => $percentage,
            'passed' => $passed,
            'answers' => $validated['answers'],
        ]);
        
        // Attribuer un badge si réussi
        if ($passed) {
            $this->awardBadge($user, 'quiz_completion', $quiz);
        }
        
        return response()->json([
            'attempt' => $attempt,
            'results' => $this->getResults($quiz, $validated['answers']),
        ]);
    }
    
    private function calculateScore(Quiz $quiz, array $answers)
    {
        $score = 0;
        
        foreach ($quiz->questions as $question) {
            $userAnswer = collect($answers)->firstWhere('question_id', $question->id);
            
            if ($userAnswer && $this->isCorrectAnswer($question, $userAnswer['answer'])) {
                $score += $question->points;
            }
        }
        
        return $score;
    }
    
    private function isCorrectAnswer($question, $answer)
    {
        $correctOption = $question->correctOption;
        
        if (!$correctOption) {
            return false;
        }
        
        if ($question->question_type === 'multiple_choice') {
            return $correctOption->id == $answer;
        } elseif ($question->question_type === 'true_false') {
            return $correctOption->is_correct == ($answer === 'true');
        }
        
        return false;
    }
}
```

---

## 🔧 Services et Logique Métier

### PayPalService
```php
<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    
    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.sandbox') 
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';
    }
    
    public function createOrder($amount, $currency = 'USD')
    {
        $accessToken = $this->getAccessToken();
        
        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                    ],
                ],
            ]);
        
        return $response->json();
    }
    
    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();
        
        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");
        
        return $response->json();
    }
    
    private function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);
        
        return $response->json('access_token');
    }
    
    public function processPayment($orderId, $userId, $courseId)
    {
        $order = $this->captureOrder($orderId);
        
        if ($order['status'] === 'COMPLETED') {
            $payment = Payment::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'amount' => $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                'currency' => $order['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                'payment_method' => 'paypal',
                'transaction_id' => $order['id'],
                'status' => 'completed',
            ]);
            
            return $payment;
        }
        
        throw new \Exception('Payment failed');
    }
}
```

### NotificationService
```php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Notifications\BadgeUnlocked;
use App\Notifications\ProgressionMilestone;

class NotificationService
{
    public function notifyBadgeUnlocked(User $user, $badge)
    {
        $user->notify(new BadgeUnlocked($badge));
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'badge_unlocked',
            'title' => 'Badge débloqué !',
            'message' => "Vous avez débloqué le badge : {$badge->name}",
            'data' => ['badge_id' => $badge->id],
        ]);
    }
    
    public function notifyProgressionMilestone(User $user, $course, $percentage)
    {
        if ($percentage === 25 || $percentage === 50 || $percentage === 75 || $percentage === 100) {
            $user->notify(new ProgressionMilestone($course, $percentage));
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'progression_milestone',
                'title' => 'Progression',
                'message' => "Vous avez complété {$percentage}% du cours : {$course->title}",
                'data' => [
                    'course_id' => $course->id,
                    'percentage' => $percentage,
                ],
            ]);
        }
    }
    
    public function notifyCoursePublished(User $user, $course)
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'course_published',
            'title' => 'Nouveau cours disponible',
            'message' => "Un nouveau cours a été publié : {$course->title}",
            'data' => ['course_id' => $course->id],
        ]);
    }
}
```

---

## 🎨 Frontend et Assets

### Structure CSS

#### Variables CSS (app.css)
```css
:root {
    /* Couleurs principales */
    --africode-primary: #1EA38B;
    --africode-secondary: #FF8E2A;
    --africode-accent-red: #E32D31;
    --africode-highlight-green: #27B371;
    
    /* Gris */
    --africode-gray-light: #F3F4F6;
    --africode-gray-medium: #D1D5DB;
    --africode-gray-dark: #6B7280;
    
    /* Typographie */
    --africode-font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    
    /* Espacements */
    --africode-spacing-xs: 0.25rem;
    --africode-spacing-sm: 0.5rem;
    --africode-spacing-md: 1rem;
    --africode-spacing-lg: 1.5rem;
    --africode-spacing-xl: 2rem;
    
    /* Transitions */
    --africode-transition: all 0.3s ease;
    
    /* Ombres */
    --africode-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --africode-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --africode-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
```

#### Composants (components/cards.css)
```css
.course-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--africode-shadow-md);
    overflow: hidden;
    transition: var(--africode-transition);
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--africode-shadow-lg);
}

.course-card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.course-card-content {
    padding: var(--africode-spacing-lg);
}

.course-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--africode-gray-dark);
    margin-bottom: var(--africode-spacing-sm);
}

.course-card-instructor {
    display: flex;
    align-items: center;
    gap: var(--africode-spacing-sm);
    margin-bottom: var(--africode-spacing-md);
}

.course-card-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--africode-primary);
}

.course-card-price.free {
    color: var(--africode-highlight-green);
}
```

### JavaScript

#### Marketing Automation (public/js/marketing-automation.js)
```javascript
class MarketingAutomation {
    constructor() {
        this.sessionTime = 0;
        this.scrollPercentage = 0;
        this.hasShownLevelTest = false;
        this.hasShownPromotions = false;
        
        this.init();
    }
    
    init() {
        // Timer de session
        setInterval(() => {
            this.sessionTime += 1;
            this.checkTriggers();
        }, 1000);
        
        // Tracking du scroll
        window.addEventListener('scroll', () => {
            this.trackScroll();
        });
    }
    
    trackScroll() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        this.scrollPercentage = (scrollTop / (documentHeight - windowHeight)) * 100;
        this.checkTriggers();
    }
    
    checkTriggers() {
        // Test de niveau après 15 secondes
        if (this.sessionTime >= 15 && !this.hasShownLevelTest) {
            this.showLevelTest();
            this.hasShownLevelTest = true;
        }
        
        // Promotions après 50-60% de scroll
        if (this.scrollPercentage >= 50 && this.scrollPercentage <= 60 && !this.hasShownPromotions) {
            this.showPromotions();
            this.hasShownPromotions = true;
        }
    }
    
    showLevelTest() {
        const popup = document.createElement('div');
        popup.className = 'marketing-popup';
        popup.innerHTML = `
            <div class="popup-content">
                <h3>Testez votre niveau !</h3>
                <p>Découvrez les formations qui vous correspondent</p>
                <button class="btn btn-primary">Commencer le test</button>
                <button class="btn btn-secondary close-popup">Plus tard</button>
            </div>
        `;
        document.body.appendChild(popup);
        
        popup.querySelector('.close-popup').addEventListener('click', () => {
            popup.remove();
        });
    }
    
    showPromotions() {
        fetch('/api/marketing/promotional-courses')
            .then(response => response.json())
            .then(courses => {
                const popup = document.createElement('div');
                popup.className = 'marketing-popup';
                popup.innerHTML = `
                    <div class="popup-content">
                        <h3>🔥 Promotions en cours !</h3>
                        <div class="promotional-courses">
                            ${courses.map(course => `
                                <div class="course-item">
                                    <h4>${course.title}</h4>
                                    <p class="price">
                                        <span class="original-price">${course.original_price} FCFA</span>
                                        <span class="discounted-price">${course.price} FCFA</span>
                                        <span class="discount">-${course.discount_percentage}%</span>
                                    </p>
                                    <a href="/courses/${course.slug}" class="btn btn-primary">Voir le cours</a>
                                </div>
                            `).join('')}
                        </div>
                        <button class="btn btn-secondary close-popup">Fermer</button>
                    </div>
                `;
                document.body.appendChild(popup);
                
                popup.querySelector('.close-popup').addEventListener('click', () => {
                    popup.remove();
                });
            });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.marketingAutomation = new MarketingAutomation();
});

// Fonction utilitaire pour générer les initiales
function getInitials(name) {
    const parts = name.trim().split(' ');
    const initials = parts.map(p => p[0].toUpperCase()).slice(0, 2).join('');
    return initials;
}
```

---

## 🔒 Sécurité

### Middleware d'Authentification
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
```

### Validation des Données
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'formateur';
    }
    
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'short_description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:1000000',
            'level' => 'required|in:debutant,intermediaire,avance,tous_niveaux',
            'is_certifying' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Le titre du cours est obligatoire',
            'description.min' => 'La description doit contenir au moins 50 caractères',
            'price.max' => 'Le prix ne peut pas dépasser 1,000,000 FCFA',
        ];
    }
}
```

### Protection CSRF
```php
// config/session.php
return [
    'same_site' => 'lax',
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
];
```

### Rate Limiting
```php
// routes/web.php
Route::middleware(['throttle:60,1'])->group(function () {
    // Routes protégées
});
```

---

## ⚡ Performance

### Cache
```php
// Cache des cours
Route::get('/courses', function() {
    return Cache::remember('courses', 3600, function() {
        return Course::with(['formateur', 'category'])->get();
    });
});

// Cache des catégories
Route::get('/categories', function() {
    return Cache::rememberForever('categories', function() {
        return Category::all();
    });
});
```

### Eager Loading
```php
// Mauvais (N+1 queries)
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->formateur->name; // Query pour chaque cours
}

// Bon (1 query)
$courses = Course::with('formateur')->get();
foreach ($courses as $course) {
    echo $course->formateur->name; // Pas de query supplémentaire
}
```

### Indexes
```sql
-- Indexes pour améliorer les performances
CREATE INDEX idx_courses_status ON courses(status);
CREATE INDEX idx_courses_category ON courses(category_id);
CREATE INDEX idx_courses_formateur ON courses(formateur_id);
CREATE INDEX idx_enrollments_user ON enrollments(user_id);
CREATE INDEX idx_enrollments_course ON enrollments(course_id);
```

---

## 🧪 Tests

### Tests Feature
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_view_courses()
    {
        $response = $this->get('/courses');
        
        $response->assertStatus(200);
        $response->assertViewIs('courses.index');
    }
    
    public function test_formateur_can_create_course()
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        
        $response = $this->actingAs($formateur)
            ->post('/courses', [
                'title' => 'Test Course',
                'description' => 'Test description',
                'category_id' => 1,
                'price' => 10000,
                'level' => 'debutant',
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course',
            'formateur_id' => $formateur->id,
        ]);
    }
    
    public function test_apprenant_can_enroll_in_course()
    {
        $apprenant = User::factory()->create(['role' => 'apprenant']);
        $course = Course::factory()->create();
        
        $response = $this->actingAs($apprenant)
            ->post("/courses/{$course->id}/enroll");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $apprenant->id,
            'course_id' => $course->id,
        ]);
    }
}
```

### Tests Unitaires
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseModelTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_course_has_formateur()
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $course = Course::factory()->create(['formateur_id' => $formateur->id]);
        
        $this->assertInstanceOf(User::class, $course->formateur);
        $this->assertEquals($formateur->id, $course->formateur->id);
    }
    
    public function test_course_has_modules()
    {
        $course = Course::factory()->create();
        $modules = $course->modules()->count();
        
        $this->assertIsInt($modules);
    }
    
    public function test_course_can_be_published()
    {
        $course = Course::factory()->create(['status' => 'draft']);
        
        $course->publish();
        
        $this->assertEquals('published', $course->status);
    }
}
```

---

## 🚀 Déploiement

### Configuration Production
```bash
# Optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Autoload
composer install --optimize-autoloader --no-dev

# Assets
npm run build

# Permissions
chmod -R 755 storage bootstrap/cache
```

### Variables d'Environnement
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://africode.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=africode_prod
DB_USERNAME=africode_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...

PAYPAL_CLIENT_ID=...
PAYPAL_CLIENT_SECRET=...
PAYPAL_SANDBOX=false
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name africode.com www.africode.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name africode.com www.africode.com;
    
    root /var/www/africode/public;
    index index.php;
    
    ssl_certificate /etc/letsencrypt/live/africode.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/africode.com/privkey.pem;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📚 Ressources Additionnelles

### Documentation
- **Laravel** : https://laravel.com/docs
- **Livewire** : https://laravel-livewire.com/docs
- **TailwindCSS** : https://tailwindcss.com/docs
- **Stripe** : https://stripe.com/docs

### Outils
- **Composer** : https://getcomposer.org
- **NPM** : https://www.npmjs.com
- **Git** : https://git-scm.com

---

**AfriCode** - Documentation Technique v1.0

*Dernière mise à jour : Janvier 2024*

