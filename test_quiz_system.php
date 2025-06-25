<?php
/**
 * Test script for Quiz System Implementation
 * Run this to verify the quiz system components are properly set up
 */

require_once 'vendor/autoload.php';

echo "=== AfriCode Quiz System Test ===\n\n";

// Test 1: Check if Quiz model exists and has required attributes
echo "1. Testing Quiz Model...\n";
try {
    $quizClass = new ReflectionClass('App\Models\Quiz');
    $fillable = $quizClass->getProperty('fillable');
    $fillable->setAccessible(true);
    
    $expectedFields = ['title', 'description', 'related_id', 'related_type', 'passing_score', 'time_limit_minutes', 'is_required'];
    $actualFields = $fillable->getValue(new App\Models\Quiz());
    
    $missingFields = array_diff($expectedFields, $actualFields);
    if (empty($missingFields)) {
        echo "   ✓ Quiz model has all required fillable fields\n";
    } else {
        echo "   ✗ Missing fillable fields: " . implode(', ', $missingFields) . "\n";
    }
    
    // Check casts
    $casts = $quizClass->getProperty('casts');
    $casts->setAccessible(true);
    $castsArray = $casts->getValue(new App\Models\Quiz());
    
    if (isset($castsArray['is_required']) && $castsArray['is_required'] === 'boolean') {
        echo "   ✓ is_required field is properly cast to boolean\n";
    } else {
        echo "   ✗ is_required field casting not found or incorrect\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error testing Quiz model: " . $e->getMessage() . "\n";
}

// Test 2: Check if controller methods exist
echo "\n2. Testing EtudiantController methods...\n";
try {
    $controllerClass = new ReflectionClass('App\Http\Controllers\EtudiantController');
    
    if ($controllerClass->hasMethod('checkQuizRequirementsForCertification')) {
        echo "   ✓ checkQuizRequirementsForCertification method exists\n";
    } else {
        echo "   ✗ checkQuizRequirementsForCertification method not found\n";
    }
    
    if ($controllerClass->hasMethod('showQuizResult')) {
        echo "   ✓ showQuizResult method exists\n";
    } else {
        echo "   ✗ showQuizResult method not found\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error testing EtudiantController: " . $e->getMessage() . "\n";
}

// Test 3: Check if views exist
echo "\n3. Testing View files...\n";

$viewFiles = [
    'resources/views/apprenants/quiz/result.blade.php',
    'resources/views/apprenants/lesson.blade.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists($viewFile)) {
        echo "   ✓ $viewFile exists\n";
        
        // Check if quiz result view has key components
        if (strpos($viewFile, 'result.blade.php') !== false) {
            $content = file_get_contents($viewFile);
            if (strpos($content, 'quiz-result') !== false && strpos($content, 'question-breakdown') !== false) {
                echo "   ✓ Quiz result view has required components\n";
            } else {
                echo "   ✗ Quiz result view missing key components\n";
            }
        }
        
        // Check if lesson view has quiz integration
        if (strpos($viewFile, 'lesson.blade.php') !== false) {
            $content = file_get_contents($viewFile);
            if (strpos($content, 'courseQuizzes') !== false && strpos($content, 'quiz-badges') !== false) {
                echo "   ✓ Lesson view has quiz integration\n";
            } else {
                echo "   ✗ Lesson view missing quiz integration\n";
            }
        }
    } else {
        echo "   ✗ $viewFile not found\n";
    }
}

// Test 4: Check migration files
echo "\n4. Testing Migration files...\n";

$migrationFiles = [
    'database/migrations/2025_06_21_073706_add_is_required_to_quizzes_table.php'
];

foreach ($migrationFiles as $migrationFile) {
    if (file_exists($migrationFile)) {
        echo "   ✓ $migrationFile exists\n";
        
        $content = file_get_contents($migrationFile);
        if (strpos($content, 'is_required') !== false && strpos($content, 'boolean') !== false) {
            echo "   ✓ Migration adds is_required boolean field\n";
        } else {
            echo "   ✗ Migration does not properly add is_required field\n";
        }
    } else {
        echo "   ✗ $migrationFile not found\n";
    }
}

// Test 5: Check seeder
echo "\n5. Testing QuizSeeder...\n";
try {
    $seederClass = new ReflectionClass('Database\Seeders\QuizSeeder');
    $runMethod = $seederClass->getMethod('run');
    
    if ($runMethod) {
        echo "   ✓ QuizSeeder run method exists\n";
        
        $seederContent = file_get_contents('database/seeders/QuizSeeder.php');
        if (strpos($seederContent, 'createModuleQuiz') !== false && strpos($seederContent, 'createCourseQuiz') !== false) {
            echo "   ✓ QuizSeeder has proper quiz creation methods\n";
        } else {
            echo "   ✗ QuizSeeder missing quiz creation methods\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error testing QuizSeeder: " . $e->getMessage() . "\n";
}

echo "\n=== Test Summary ===\n";
echo "Quiz system implementation verification completed.\n";
echo "Check the results above to ensure all components are properly implemented.\n";
echo "\nNext steps:\n";
echo "1. Run 'php artisan migrate' to add the is_required field\n";
echo "2. Run 'php artisan db:seed --class=QuizSeeder' to populate quiz data\n";
echo "3. Test the complete workflow in the application\n";
?>
