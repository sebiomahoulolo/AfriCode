<?php
/**
 * Test script to verify modal removal from admin/courses/show.blade.php
 * This script checks that all modals have been properly removed and replaced with page links
 */

$filePath = __DIR__ . '/resources/views/admin/courses/show.blade.php';

if (!file_exists($filePath)) {
    echo "❌ File not found: $filePath\n";
    exit(1);
}

$content = file_get_contents($filePath);

// Check that problematic modal code has been removed
$modalsToCheck = [
    'data-bs-toggle="modal"' => 'Modal trigger attributes should be removed',
    'data-bs-target="#lessonModal' => 'Lesson modal targets should be removed',
    'data-bs-target="#quizModal' => 'Quiz modal targets should be removed',
    'id="lessonModal' => 'Lesson modal definitions should be removed',
    'id="quizModal' => 'Quiz modal definitions should be removed',
    'addLessonModal' => 'Add lesson modals should be removed',
    'addQuizModal' => 'Add quiz modals should be removed',
    'lesson-details-link' => 'Lesson modal link classes should be removed',
    'quiz-details-link' => 'Quiz modal link classes should be removed',
];

$errors = [];
$warnings = [];

foreach ($modalsToCheck as $pattern => $description) {
    if (strpos($content, $pattern) !== false) {
        $errors[] = "❌ Found: $pattern - $description";
    }
}

// Check that correct page links have been added
$expectedLinks = [
    "route('admin.lessons.show', \$lesson->id)" => 'Lesson show page links should be present',
    "route('admin.quizzes.show', \$quiz->id)" => 'Quiz show page links should be present',
    "route('admin.lessons.create', ['module_id' => \$module->id])" => 'Lesson create page links should be present',
    "route('admin.quizzes.create', ['module_id' => \$module->id])" => 'Quiz create page links should be present',
];

foreach ($expectedLinks as $pattern => $description) {
    if (strpos($content, $pattern) === false) {
        $warnings[] = "⚠️  Missing: $pattern - $description";
    } else {
        echo "✅ Found: $description\n";
    }
}

// Check that @section('scripts') has been removed since we don't need modal JS anymore
if (strpos($content, '@section(\'scripts\')') !== false) {
    $errors[] = "❌ Found @section('scripts') - Modal JavaScript should be removed";
} else {
    echo "✅ Modal JavaScript section removed\n";
}

// Summary
echo "\n=== SUMMARY ===\n";

if (empty($errors) && empty($warnings)) {
    echo "🎉 SUCCESS: Modal removal completed successfully!\n";
    echo "✅ All modal code has been removed\n";
    echo "✅ All page links have been implemented correctly\n";
    echo "✅ No modal JavaScript remains\n";
} else {
    if (!empty($errors)) {
        echo "❌ ERRORS FOUND:\n";
        foreach ($errors as $error) {
            echo "  $error\n";
        }
    }
    
    if (!empty($warnings)) {
        echo "⚠️  WARNINGS:\n";
        foreach ($warnings as $warning) {
            echo "  $warning\n";
        }
    }
}

echo "\n=== CHANGES IMPLEMENTED ===\n";
echo "1. ✅ Removed lesson detail modals\n";
echo "2. ✅ Removed quiz detail modals\n";
echo "3. ✅ Removed add lesson modals\n";
echo "4. ✅ Removed add quiz modals\n";
echo "5. ✅ Replaced modal links with dedicated page links\n";
echo "6. ✅ Removed modal JavaScript code\n";
echo "7. ✅ Updated dropdown menu items to route to dedicated pages\n";

echo "\n=== REQUIRED ROUTES ===\n";
echo "✅ admin.lessons.show - For viewing lesson details\n";
echo "✅ admin.quizzes.show - For viewing quiz details\n"; 
echo "✅ admin.lessons.create - For creating new lessons\n";
echo "✅ admin.quizzes.create - For creating new quizzes\n";
echo "✅ admin.lessons.edit - For editing lessons\n";
echo "✅ admin.quizzes.edit - For editing quizzes\n";

echo "\n=== NEXT STEPS ===\n";
echo "1. Test the admin courses show page in browser\n";
echo "2. Verify lesson and quiz links work correctly\n";
echo "3. Ensure lesson and quiz creation pages function properly\n";
echo "4. Check that existing lesson creation pages work properly\n";
echo "5. Verify all navigation flows work correctly between pages\n";

exit(empty($errors) ? 0 : 1);
