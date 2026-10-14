<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;

class DiagnoseLessonUnlocking extends Command
{
    protected $signature = 'lesson:diagnose-unlocking {--user-id=} {--course-id=}';
    protected $description = 'Diagnostique les problèmes de déblocage des leçons';

    public function handle()
    {
        $userId = $this->option('user-id');
        $courseId = $this->option('course-id');

        if (!$userId || !$courseId) {
            $this->error('Veuillez spécifier --user-id et --course-id');
            return 1;
        }

        $user = User::find($userId);
        $course = Course::find($courseId);

        if (!$user || !$course) {
            $this->error('Utilisateur ou cours non trouvé');
            return 1;
        }

        $this->info("🔍 Diagnostic pour l'utilisateur: {$user->first_name} {$user->last_name}");
        $this->info("📚 Cours: {$course->title}");
        $this->newLine();

        // Vérifier l'inscription
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if (!$enrollment) {
            $this->error('❌ Aucune inscription trouvée pour ce cours');
            return 1;
        }

        $this->info("✅ Inscription trouvée: {$enrollment->id}");
        $this->newLine();

        // Analyser chaque module et leçon
        $course->load(['modules' => fn($q) => $q->orderBy('order'), 'modules.lessons' => fn($q) => $q->orderBy('order')]);

        $previousModuleCompleted = true;

        foreach ($course->modules as $moduleIndex => $module) {
            $this->info("📖 Module {$module->order}: {$module->title}");
            $this->line("  🔓 Previous module completed: " . ($previousModuleCompleted ? 'YES' : 'NO'));
            
            $allLessonsInModuleCompleted = true;
            
            foreach ($module->lessons as $lessonIndex => $lesson) {
                $isCompleted = $lesson->isCompletedBy($user->id);
                $completion = LessonCompletion::where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->first();
                
                $status = $previousModuleCompleted ? 'UNLOCKED' : 'LOCKED';
                $completionStatus = $isCompleted ? 'COMPLETED' : 'NOT COMPLETED';
                
                $this->line("  📝 Leçon {$lesson->order}: {$lesson->title}");
                $this->line("     Status: {$status}");
                $this->line("     Complétion: {$completionStatus}");
                $this->line("     Type: {$lesson->content_type}");
                
                if ($completion) {
                    $this->line("     Completion ID: {$completion->id}");
                    $this->line("     Completed at: " . ($completion->completed_at ? $completion->completed_at->format('Y-m-d H:i:s') : 'NULL'));
                    $this->line("     Can complete: " . ($completion->can_complete ? 'YES' : 'NO'));
                    $this->line("     Watch time: {$completion->watch_time_seconds}s");
                } else {
                    $this->line("     Aucun enregistrement de complétion");
                }
                
                if (!$isCompleted) {
                    $allLessonsInModuleCompleted = false;
                }
                
                $this->newLine();
            }
            
            // Vérifier le quiz du module
            if ($module->quiz) {
                $quizPassed = $module->quiz->isPassedByUser($user->id);
                $quizStatus = $previousModuleCompleted && $allLessonsInModuleCompleted ? 'UNLOCKED' : 'LOCKED';
                
                $this->line("  🧠 Quiz: {$module->quiz->title}");
                $this->line("     Status: {$quizStatus}");
                $this->line("     Passed: " . ($quizPassed ? 'YES' : 'NO'));
                $this->newLine();
            }
            
            // Mettre à jour pour le module suivant
            // Le quiz n'est pas obligatoire pour débloquer le module suivant
            $previousModuleCompleted = $allLessonsInModuleCompleted;
            $this->line("  ✅ All lessons completed: " . ($allLessonsInModuleCompleted ? 'YES' : 'NO'));
            $this->line("  🧠 Quiz passed: " . ($module->quiz ? ($module->quiz->isPassedByUser($user->id) ? 'YES' : 'NO') : 'NO QUIZ'));
            $this->line("  🔓 Next module will be: " . ($previousModuleCompleted ? 'UNLOCKED' : 'LOCKED'));
        }

        $this->info("🎯 Résumé:");
        $this->info("   - Modules total: " . $course->modules->count());
        $this->info("   - Leçons total: " . $course->modules->flatMap->lessons->count());
        
        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();
            
        $this->info("   - Leçons complétées: {$completedLessons}");

        return 0;
    }
}
