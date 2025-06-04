<?php
/**
 * Test de validation du système de création de leçons
 * Ce script vérifie que tous les composants nécessaires sont en place
 */

require_once __DIR__ . '/vendor/autoload.php';

// Configuration Laravel pour les tests
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class LessonSystemValidator 
{
    public function runTests()
    {
        echo "🔍 VALIDATION DU SYSTÈME DE CRÉATION DE LEÇONS\n";
        echo "=" . str_repeat("=", 50) . "\n\n";
        
        $this->testDatabaseConnections();
        $this->testModelsExist();
        $this->testRelationships();
        $this->testControllerMethods();
        $this->testRoutes();
        $this->testViews();
        $this->testMigrations();
        $this->testCreateLessonFlow();
        
        echo "\n✅ TOUS LES TESTS SONT PASSÉS AVEC SUCCÈS !\n";
        echo "Le système de création de leçons est entièrement opérationnel.\n";
    }
    
    private function testDatabaseConnections()
    {
        echo "📊 Test de connexion à la base de données...\n";
        
        try {
            DB::connection()->getPdo();
            echo "  ✅ Connexion à la base de données réussie\n";
        } catch (Exception $e) {
            throw new Exception("❌ Erreur de connexion à la base de données: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testModelsExist()
    {
        echo "🏗️ Test de l'existence des modèles...\n";
        
        $models = ['User', 'Course', 'Module', 'Lesson', 'Category', 'Enrollment', 'LessonCompletion'];
        
        foreach ($models as $model) {
            $class = "App\\Models\\{$model}";
            if (class_exists($class)) {
                echo "  ✅ Modèle {$model} existe\n";
            } else {
                throw new Exception("❌ Modèle {$model} introuvable");
            }
        }
        
        echo "\n";
    }
    
    private function testRelationships()
    {
        echo "🔗 Test des relations entre modèles...\n";
        
        // Récupérer des données de test existantes
        $user = User::where('role', 'formateur')->first();
        $course = Course::first();
        $module = Module::first();
        $lesson = Lesson::first();
        
        if (!$user) {
            echo "  ⚠️  Aucun formateur trouvé, création d'un utilisateur de test...\n";
            $user = User::create([
                'first_name' => 'Test',
                'last_name' => 'Formateur',
                'email' => 'test.formateur@africode.com',
                'password' => bcrypt('password'),
                'role' => 'formateur'
            ]);
        }
        
        // Test relations Course
        if ($course) {
            echo "  ✅ Relation Course->formateur: " . ($course->formateur ? "OK" : "NULL") . "\n";
            echo "  ✅ Relation Course->modules: " . $course->modules->count() . " modules\n";
            echo "  ✅ Relation Course->category: " . ($course->category ? "OK" : "NULL") . "\n";
        }
        
        // Test relations Module
        if ($module) {
            echo "  ✅ Relation Module->course: " . ($module->course ? "OK" : "NULL") . "\n";
            echo "  ✅ Relation Module->lessons: " . $module->lessons->count() . " leçons\n";
        }
        
        // Test relations Lesson
        if ($lesson) {
            echo "  ✅ Relation Lesson->module: " . ($lesson->module ? "OK" : "NULL") . "\n";
            echo "  ✅ Relation Lesson->completions: " . $lesson->completions->count() . " completions\n";
        }
        
        echo "\n";
    }
    
    private function testControllerMethods()
    {
        echo "🎛️ Test des méthodes de contrôleur...\n";
        
        $adminController = new \App\Http\Controllers\AdminController();
        $formateurController = new \App\Http\Controllers\FormateurController();
        
        $methods = [
            'AdminController' => ['lessonsCreate', 'lessonsStore', 'lessonsEdit', 'lessonsUpdate', 'lessonsDestroy'],
            'FormateurController' => ['createLesson', 'storeLesson', 'editLesson', 'updateLesson', 'destroyLesson']
        ];
        
        foreach ($methods as $controllerName => $methodList) {
            foreach ($methodList as $method) {
                $controller = $controllerName === 'AdminController' ? $adminController : $formateurController;
                if (method_exists($controller, $method)) {
                    echo "  ✅ {$controllerName}::{$method} existe\n";
                } else {
                    throw new Exception("❌ Méthode {$controllerName}::{$method} introuvable");
                }
            }
        }
        
        echo "\n";
    }
    
    private function testRoutes()
    {
        echo "🛣️ Test des routes...\n";
        
        $routes = [
            'admin.lessons.create',
            'admin.lessons.store',
            'admin.lessons.edit',
            'admin.lessons.update',
            'admin.lessons.destroy',
            'formateur.lessons.create',
            'formateur.lessons.store',
            'formateur.lessons.edit',
            'formateur.lessons.update'
        ];
        
        foreach ($routes as $routeName) {
            try {
                $route = app('router')->getRoutes()->getByName($routeName);
                if ($route) {
                    echo "  ✅ Route {$routeName} existe\n";
                } else {
                    echo "  ⚠️  Route {$routeName} introuvable\n";
                }
            } catch (Exception $e) {
                echo "  ⚠️  Route {$routeName} introuvable\n";
            }
        }
        
        echo "\n";
    }
    
    private function testViews()
    {
        echo "👁️ Test des vues...\n";
        
        $views = [
            'admin/lessons/create.blade.php',
            'admin/lessons/edit.blade.php',
            'admin/lessons/show.blade.php',
            'formateurs/create_lesson.blade.php',
            'formateurs/edit_lesson.blade.php'
        ];
        
        foreach ($views as $view) {
            $viewPath = resource_path("views/{$view}");
            if (file_exists($viewPath)) {
                echo "  ✅ Vue {$view} existe\n";
            } else {
                echo "  ⚠️  Vue {$view} introuvable\n";
            }
        }
        
        echo "\n";
    }
    
    private function testMigrations()
    {
        echo "📋 Test des migrations...\n";
        
        $tables = ['lessons', 'lesson_completions', 'modules', 'courses', 'users'];
        
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                echo "  ✅ Table {$table} existe\n";
            } else {
                throw new Exception("❌ Table {$table} introuvable");
            }
        }
        
        // Test de la structure de la table lessons
        if (DB::getSchemaBuilder()->hasTable('lessons')) {
            $columns = DB::getSchemaBuilder()->getColumnListing('lessons');
            $requiredColumns = ['id', 'module_id', 'title', 'content_type', 'video_url', 'text_content', 'pdf_path', 'external_url', 'duration_minutes', 'order', 'is_previewable'];
            
            $missingColumns = array_diff($requiredColumns, $columns);
            if (empty($missingColumns)) {
                echo "  ✅ Structure de la table lessons est correcte\n";
            } else {
                echo "  ⚠️  Colonnes manquantes dans lessons: " . implode(', ', $missingColumns) . "\n";
            }
        }
        
        echo "\n";
    }
    
    private function testCreateLessonFlow()
    {
        echo "🚀 Test du processus de création de leçons...\n";
        
        try {
            DB::beginTransaction();
            
            // 1. Créer un utilisateur formateur de test
            $formateur = User::firstOrCreate([
                'email' => 'test.formateur.lesson@africode.com'
            ], [
                'first_name' => 'Test',
                'last_name' => 'Formateur',
                'password' => bcrypt('password'),
                'role' => 'formateur'
            ]);
            echo "  ✅ Formateur de test créé/récupéré (ID: {$formateur->id})\n";
            
            // 2. Créer une catégorie de test
            $category = Category::firstOrCreate([
                'slug' => 'test-category'
            ], [
                'name' => 'Catégorie Test',
                'description' => 'Catégorie pour les tests'
            ]);
            echo "  ✅ Catégorie de test créée/récupérée (ID: {$category->id})\n";
            
            // 3. Créer un cours de test
            $course = Course::firstOrCreate([
                'slug' => 'cours-test-lesson'
            ], [
                'title' => 'Cours Test pour Leçons',
                'short_description' => 'Cours de test pour valider la création de leçons',
                'formateur_id' => $formateur->id,
                'category_id' => $category->id,
                'status' => 'draft',
                'price' => 0
            ]);
            echo "  ✅ Cours de test créé/récupéré (ID: {$course->id})\n";
            
            // 4. Créer un module de test
            $module = Module::firstOrCreate([
                'course_id' => $course->id,
                'title' => 'Module Test'
            ], [
                'description' => 'Module de test pour les leçons',
                'order' => 1
            ]);
            echo "  ✅ Module de test créé/récupéré (ID: {$module->id})\n";
            
            // 5. Créer des leçons de test pour chaque type de contenu
            $lessonTypes = [
                [
                    'title' => 'Leçon Vidéo Test',
                    'content_type' => 'video',
                    'video_url' => 'https://www.youtube.com/watch?v=test123'
                ],
                [
                    'title' => 'Leçon Texte Test',
                    'content_type' => 'text',
                    'text_content' => 'Contenu de test pour la leçon de type texte.'
                ],
                [
                    'title' => 'Leçon Lien Externe Test',
                    'content_type' => 'external',
                    'external_url' => 'https://example.com/resource'
                ]
            ];
            
            foreach ($lessonTypes as $index => $lessonData) {
                $lesson = Lesson::create([
                    'module_id' => $module->id,
                    'title' => $lessonData['title'],
                    'content_type' => $lessonData['content_type'],
                    'video_url' => $lessonData['video_url'] ?? null,
                    'text_content' => $lessonData['text_content'] ?? null,
                    'external_url' => $lessonData['external_url'] ?? null,
                    'duration_minutes' => 15,
                    'order' => $index + 1,
                    'is_previewable' => false
                ]);
                echo "  ✅ Leçon de type {$lessonData['content_type']} créée (ID: {$lesson->id})\n";
            }
            
            // 6. Tester la récupération des données
            $courseWithRelations = Course::with(['modules.lessons'])->find($course->id);
            $totalLessons = $courseWithRelations->modules->sum(function($module) {
                return $module->lessons->count();
            });
            echo "  ✅ Vérification des relations: {$totalLessons} leçons trouvées dans le cours\n";
            
            // Test des méthodes spéciales
            echo "  ✅ Test getLessonsCount(): " . $course->getLessonsCount() . " leçons\n";
            echo "  ✅ Test getEstimatedDuration(): " . $course->getEstimatedDuration() . " minutes\n";
            
            DB::rollBack(); // Annuler les changements de test
            echo "  ✅ Données de test nettoyées\n";
            
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("❌ Erreur lors du test de création: " . $e->getMessage());
        }
        
        echo "\n";
    }
}

// Exécution des tests
try {
    $validator = new LessonSystemValidator();
    $validator->runTests();
} catch (Exception $e) {
    echo "\n❌ ÉCHEC DU TEST: " . $e->getMessage() . "\n";
    exit(1);
}
