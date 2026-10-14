<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserQuizAnswer;

class DiagnoseQuizIssues extends Command
{
    protected $signature = 'quiz:diagnose {--user-id=} {--course-id=}';
    protected $description = 'Diagnostique les problèmes avec les quiz et examens';

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

        $this->info("🔍 Diagnostic des quiz pour l'utilisateur: {$user->first_name} {$user->last_name}");
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

        // Analyser les quiz de modules
        $course->load(['modules' => fn($q) => $q->orderBy('order'), 'modules.quiz', 'finalQuiz']);

        foreach ($course->modules as $module) {
            if ($module->quiz) {
                $this->info("🧠 Quiz du Module {$module->order}: {$module->quiz->title}");
                $this->line("  📊 Score de passage requis: {$module->quiz->passing_score}%");
                $this->line("  🔄 Nombre max de tentatives: " . ($module->quiz->max_attempts ?? 'Illimité'));
                $this->line("  ⚡ Obligatoire: " . ($module->quiz->is_required ? 'OUI' : 'NON'));
                
                // Vérifier les tentatives
                $attempts = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $module->quiz->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $this->line("  📝 Tentatives: {$attempts->count()}");
                
                foreach ($attempts as $index => $attempt) {
                    $this->line("    Tentative " . ($index + 1) . ":");
                    $this->line("      Score: {$attempt->score}%");
                    $this->line("      Statut: " . ($attempt->submitted_at ? 'TERMINÉ' : 'EN COURS'));
                    $this->line("      Date: " . $attempt->created_at->format('Y-m-d H:i:s'));
                    $this->line("      Réussi: " . ($attempt->score >= $module->quiz->passing_score ? 'OUI' : 'NON'));
                    
                    // Vérifier les réponses
                    $answers = UserQuizAnswer::where('quiz_attempt_id', $attempt->id)->get();
                    $correctAnswers = $answers->where('is_correct', true)->count();
                    $totalAnswers = $answers->count();
                    
                    $this->line("      Réponses correctes: {$correctAnswers}/{$totalAnswers}");
                }
                
                // Vérifier si le quiz est réussi
                $isPassed = $module->quiz->isPassedByUser($user->id);
                $this->line("  ✅ Quiz réussi: " . ($isPassed ? 'OUI' : 'NON'));
                
                // Vérifier l'accès
                $canAccess = $module->quiz->canBeAccessedByUser($user->id);
                $this->line("  🔓 Accès autorisé: " . ($canAccess ? 'OUI' : 'NON'));
                
                $this->newLine();
            }
        }

        // Analyser l'examen final
        if ($course->finalQuiz) {
            $this->info("🎯 Examen Final: {$course->finalQuiz->title}");
            $this->line("  📊 Score de passage requis: {$course->finalQuiz->passing_score}%");
            $this->line("  🔄 Nombre max de tentatives: " . ($course->finalQuiz->max_attempts ?? 'Illimité'));
            $this->line("  ⚡ Obligatoire: " . ($course->finalQuiz->is_required ? 'OUI' : 'NON'));
            
            // Vérifier les tentatives
            $attempts = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $course->finalQuiz->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $this->line("  📝 Tentatives: {$attempts->count()}");
            
            foreach ($attempts as $index => $attempt) {
                $this->line("    Tentative " . ($index + 1) . ":");
                $this->line("      Score: {$attempt->score}%");
                $this->line("      Statut: " . ($attempt->submitted_at ? 'TERMINÉ' : 'EN COURS'));
                $this->line("      Date: " . $attempt->created_at->format('Y-m-d H:i:s'));
                $this->line("      Réussi: " . ($attempt->score >= $course->finalQuiz->passing_score ? 'OUI' : 'NON'));
            }
            
            // Vérifier si l'examen est réussi
            $isPassed = $course->finalQuiz->isPassedByUser($user->id);
            $this->line("  ✅ Examen réussi: " . ($isPassed ? 'OUI' : 'NON'));
            
            // Vérifier l'accès
            $canAccess = $course->finalQuiz->canBeAccessedByUser($user->id);
            $this->line("  🔓 Accès autorisé: " . ($canAccess ? 'OUI' : 'NON'));
            
            $this->newLine();
        }

        // Vérifier l'état de certification
        $certification = $user->certifications()->where('course_id', $course->id)->first();
        if ($certification) {
            $this->info("🏆 Certificat: OBTENU");
            $this->line("  📅 Délivré le: " . $certification->issued_at->format('Y-m-d H:i:s'));
            $this->line("  🆔 ID: {$certification->certificate_identifier}");
        } else {
            $this->info("🏆 Certificat: NON OBTENU");
            
            // Vérifier pourquoi le certificat n'est pas obtenu
            $allModulesCompleted = $course->allModulesCompletedByUser($user->id);
            $finalQuizPassed = $course->finalQuizPassedByUser($user->id);
            
            $this->line("  📚 Tous les modules complétés: " . ($allModulesCompleted ? 'OUI' : 'NON'));
            $this->line("  🎯 Examen final réussi: " . ($finalQuizPassed ? 'OUI' : 'NON'));
        }

        return 0;
    }
}
