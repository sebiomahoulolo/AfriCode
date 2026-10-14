<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Course;
use App\Models\User;

class DiagnoseCourseProgress extends Command
{
    protected $signature = 'course:diagnose {--user-id=} {--course-id=} {--fix}';
    protected $description = 'Diagnostique les problèmes de progression des cours';

    public function handle()
    {
        $userId = $this->option('user-id');
        $courseId = $this->option('course-id');
        $fix = $this->option('fix');

        $this->info('🔍 Diagnostic des problèmes de progression des cours...');

        if ($userId && $courseId) {
            $this->diagnoseUserCourse($userId, $courseId, $fix);
        } elseif ($userId) {
            $this->diagnoseUser($userId, $fix);
        } elseif ($courseId) {
            $this->diagnoseCourse($courseId, $fix);
        } else {
            $this->diagnoseAll($fix);
        }
    }

    private function diagnoseUserCourse($userId, $courseId, $fix)
    {
        $user = User::find($userId);
        $course = Course::find($courseId);
        $enrollment = Enrollment::where('user_id', $userId)->where('course_id', $courseId)->first();

        if (!$user || !$course || !$enrollment) {
            $this->error('Utilisateur, cours ou inscription non trouvé.');
            return;
        }

        $this->info("📊 Diagnostic pour {$user->first_name} {$user->last_name} - {$course->title}");

        // Vérifier les leçons complétées
        $totalLessons = $course->getLessonsCount();
        $completedLessons = LessonCompletion::where('user_id', $userId)
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();

        $this->line("📚 Leçons: {$completedLessons}/{$totalLessons} complétées");

        // Vérifier les problèmes
        $problems = [];

        // Problème 1: Leçons marquées comme complétées mais sans completed_at
        $incompleteCompletions = LessonCompletion::where('user_id', $userId)
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->whereNull('completed_at')
            ->where('can_complete', true)
            ->count();

        if ($incompleteCompletions > 0) {
            $problems[] = "⚠️  {$incompleteCompletions} leçons marquées comme complétables mais sans completed_at";
        }

        // Problème 2: Progression incorrecte
        $calculatedProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        if ($enrollment->progress_percentage != $calculatedProgress) {
            $problems[] = "⚠️  Progression incorrecte: {$enrollment->progress_percentage}% vs {$calculatedProgress}% calculé";
        }

        // Problème 3: Certificat manquant
        if ($course->is_certifying && $completedLessons == $totalLessons) {
            $certification = \App\Models\Certification::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();
            
            if (!$certification) {
                $problems[] = "⚠️  Certificat manquant pour un cours certifiant complété";
            }
        }

        if (empty($problems)) {
            $this->info("✅ Aucun problème détecté!");
        } else {
            $this->warn("🚨 Problèmes détectés:");
            foreach ($problems as $problem) {
                $this->line("   {$problem}");
            }

            if ($fix) {
                $this->fixUserCourse($userId, $courseId, $problems);
            }
        }
    }

    private function fixUserCourse($userId, $courseId, $problems)
    {
        $this->info("🔧 Correction des problèmes...");

        foreach ($problems as $problem) {
            if (str_contains($problem, 'completed_at')) {
                // Corriger les leçons sans completed_at
                LessonCompletion::where('user_id', $userId)
                    ->whereIn('lesson_id', Course::find($courseId)->modules->flatMap->lessons->pluck('id'))
                    ->whereNull('completed_at')
                    ->where('can_complete', true)
                    ->update(['completed_at' => now()]);
                
                $this->line("   ✅ Corrigé: completed_at ajouté aux leçons");
            }

            if (str_contains($problem, 'Progression incorrecte')) {
                // Recalculer la progression
                $enrollment = Enrollment::where('user_id', $userId)->where('course_id', $courseId)->first();
                $enrollment->updateProgress();
                $this->line("   ✅ Corrigé: Progression recalculée");
            }

            if (str_contains($problem, 'Certificat manquant')) {
                // Générer le certificat
                $enrollment = Enrollment::where('user_id', $userId)->where('course_id', $courseId)->first();
                $controller = new \App\Http\Controllers\EtudiantController();
                $reflection = new \ReflectionClass($controller);
                $method = $reflection->getMethod('completeEnrollmentAndGenerateCertificate');
                $method->setAccessible(true);
                $method->invoke($controller, $enrollment);
                $this->line("   ✅ Corrigé: Certificat généré");
            }
        }
    }

    private function diagnoseUser($userId, $fix)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->error('Utilisateur non trouvé.');
            return;
        }

        $enrollments = Enrollment::where('user_id', $userId)->with('course')->get();
        
        $this->info("📊 Diagnostic pour {$user->first_name} {$user->last_name}");
        $this->line("📚 Inscriptions: {$enrollments->count()}");

        foreach ($enrollments as $enrollment) {
            $this->diagnoseUserCourse($userId, $enrollment->course_id, $fix);
        }
    }

    private function diagnoseCourse($courseId, $fix)
    {
        $course = Course::find($courseId);
        if (!$course) {
            $this->error('Cours non trouvé.');
            return;
        }

        $enrollments = Enrollment::where('course_id', $courseId)->with('user')->get();
        
        $this->info("📊 Diagnostic pour le cours: {$course->title}");
        $this->line("👥 Inscriptions: {$enrollments->count()}");

        foreach ($enrollments as $enrollment) {
            $this->diagnoseUserCourse($enrollment->user_id, $courseId, $fix);
        }
    }

    private function diagnoseAll($fix)
    {
        $this->info("📊 Diagnostic global de tous les cours...");
        
        $totalEnrollments = Enrollment::count();
        $this->line("👥 Total des inscriptions: {$totalEnrollments}");

        $problematicEnrollments = Enrollment::where('progress_percentage', 0)
            ->whereHas('course.modules.lessons')
            ->count();

        if ($problematicEnrollments > 0) {
            $this->warn("⚠️  {$problematicEnrollments} inscriptions avec progression à 0%");
        }

        if ($fix) {
            $this->info("🔧 Correction globale...");
            // Exécuter la migration de correction
            $this->call('migrate', ['--path' => 'database/migrations/2025_01_20_000000_fix_lesson_completions_data.php']);
        }
    }
}
