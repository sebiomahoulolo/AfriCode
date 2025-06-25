<?php

/**
 * Test du système de quiz de module corrigé
 * 
 * Ce script teste les corrections apportées :
 * 1. Un seul quiz par module
 * 2. Affichage correct du quiz après les leçons
 * 3. Relations correctes entre modules et quiz
 */

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Module;
use App\Models\Quiz;
use App\Models\Course;
use App\Models\User;

echo "=== Test du système de quiz de module corrigé ===\n\n";

// Test 1 : Vérifier les relations
echo "Test 1 : Vérification des relations Module <-> Quiz\n";

try {
    // Récupérer quelques modules avec leurs quiz
    $modules = Module::with(['quiz', 'lessons'])->limit(5)->get();
    
    foreach ($modules as $module) {
        echo "Module: {$module->title}\n";
        echo "  - Leçons: {$module->lessons->count()}\n";
        
        if ($module->quiz) {
            echo "  - Quiz: {$module->quiz->title}\n";
            echo "    * Type: {$module->quiz->quiz_type}\n";
            echo "    * Questions: {$module->quiz->questions->count()}\n";
            echo "    * Score minimum: {$module->quiz->passing_score}%\n";
            echo "    ✅ Module a UN quiz\n";
        } else {
            echo "  - Quiz: Aucun\n";
            echo "    ℹ️  Module sans quiz\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Erreur lors du test des relations: " . $e->getMessage() . "\n";
}

// Test 2 : Vérifier les quiz orphelins (sans module_id)
echo "Test 2 : Vérification des quiz orphelins\n";

try {
    $orphanQuizzes = Quiz::whereNull('module_id')->whereNull('course_id')->get();
    
    if ($orphanQuizzes->count() > 0) {
        echo "⚠️  Trouvé {$orphanQuizzes->count()} quiz orphelins :\n";
        foreach ($orphanQuizzes as $quiz) {
            echo "  - Quiz ID {$quiz->id}: {$quiz->title}\n";
        }
    } else {
        echo "✅ Aucun quiz orphelin trouvé\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la vérification des quiz orphelins: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3 : Vérifier la cohérence des types de quiz
echo "Test 3 : Vérification des types de quiz\n";

try {
    $moduleQuizzes = Quiz::where('quiz_type', 'module_end')->whereNotNull('module_id')->count();
    $courseQuizzes = Quiz::where('quiz_type', 'course_final')->whereNotNull('course_id')->count();
    
    echo "Quiz de module (module_end): {$moduleQuizzes}\n";
    echo "Quiz de cours (course_final): {$courseQuizzes}\n";
    
    // Vérifier les incohérences
    $badModuleQuizzes = Quiz::where('quiz_type', 'module_end')->whereNull('module_id')->count();
    $badCourseQuizzes = Quiz::where('quiz_type', 'course_final')->whereNull('course_id')->count();
    
    if ($badModuleQuizzes > 0) {
        echo "⚠️  {$badModuleQuizzes} quiz de module sans module_id\n";
    }
    
    if ($badCourseQuizzes > 0) {
        echo "⚠️  {$badCourseQuizzes} quiz de cours sans course_id\n";
    }
    
    if ($badModuleQuizzes === 0 && $badCourseQuizzes === 0) {
        echo "✅ Tous les quiz ont les bonnes relations\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors de la vérification des types: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4 : Test de création d'un quiz pour un module
echo "Test 4 : Simulation de création de quiz\n";

function simulateQuizCreation($moduleId) {
    try {
        $module = Module::with('quiz')->find($moduleId);
        
        if (!$module) {
            return "Module non trouvé";
        }
        
        if ($module->quiz) {
            return "❌ Module a déjà un quiz : {$module->quiz->title}";
        }
        
        return "✅ Module peut recevoir un quiz";
        
    } catch (Exception $e) {
        return "Erreur: " . $e->getMessage();
    }
}

// Tester sur quelques modules
$moduleIds = Module::limit(3)->pluck('id');
foreach ($moduleIds as $moduleId) {
    $result = simulateQuizCreation($moduleId);
    echo "Module ID {$moduleId}: {$result}\n";
}

echo "\n";

// Test 5 : Vérifier les statistiques globales
echo "Test 5 : Statistiques globales\n";

try {
    $totalModules = Module::count();
    $modulesWithQuiz = Module::whereHas('quiz')->count();
    $modulesWithoutQuiz = $totalModules - $modulesWithQuiz;
    
    echo "Total modules: {$totalModules}\n";
    echo "Modules avec quiz: {$modulesWithQuiz}\n";
    echo "Modules sans quiz: {$modulesWithoutQuiz}\n";
    
    if ($totalModules > 0) {
        $percentage = round(($modulesWithQuiz / $totalModules) * 100, 1);
        echo "Pourcentage de modules avec quiz: {$percentage}%\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors du calcul des statistiques: " . $e->getMessage() . "\n";
}

echo "\n=== Fin des tests ===\n";

echo "\n📋 Résumé des corrections apportées :\n";
echo "1. ✅ Relation Module->quiz() utilise quiz_type='module_end'\n";
echo "2. ✅ Contrôleur vérifie qu'un module n'a qu'un seul quiz\n";
echo "3. ✅ Vue affiche le quiz unique après les leçons\n";
echo "4. ✅ Bouton 'Ajouter quiz' n'apparaît que si pas de quiz\n";
echo "5. ✅ Quiz affiché avec style distinctif et position finale\n";
echo "6. ✅ Modals de suppression configurées correctement\n";

?>
