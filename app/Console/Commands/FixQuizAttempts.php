<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizAttempt;
use App\Models\UserQuizAnswer;

class FixQuizAttempts extends Command
{
    protected $signature = 'quiz:fix-attempts {--user-id=} {--course-id=}';
    protected $description = 'Corrige les tentatives de quiz qui ont des problèmes de statut';

    public function handle()
    {
        $userId = $this->option('user-id');
        $courseId = $this->option('course-id');

        $this->info('🔧 Correction des tentatives de quiz...');

        $query = QuizAttempt::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($courseId) {
            $query->whereHas('quiz', function($q) use ($courseId) {
                $q->where('course_id', $courseId)
                  ->orWhereHas('module', function($mq) use ($courseId) {
                      $mq->where('course_id', $courseId);
                  });
            });
        }

        $attempts = $query->get();
        
        $this->info("📊 Trouvé {$attempts->count()} tentatives à analyser");
        
        $fixed = 0;
        
        foreach ($attempts as $attempt) {
            $needsFix = false;
            $updates = [];
            
            // Vérifier si la tentative a des réponses mais n'est pas marquée comme soumise
            $hasAnswers = UserQuizAnswer::where('quiz_attempt_id', $attempt->id)->exists();
            
            if ($hasAnswers && !$attempt->submitted_at) {
                $updates['submitted_at'] = $attempt->created_at;
                $needsFix = true;
            }
            
            // Vérifier si la tentative a un score mais n'est pas marquée comme complétée
            if ($attempt->score !== null && $attempt->status !== 'completed') {
                $updates['status'] = 'completed';
                $updates['completed_at'] = $attempt->created_at;
                $needsFix = true;
            }
            
            // Vérifier si la tentative est marquée comme complétée mais n'a pas de score
            if ($attempt->status === 'completed' && $attempt->score === null) {
                // Calculer le score basé sur les réponses
                $answers = UserQuizAnswer::where('quiz_attempt_id', $attempt->id)->get();
                $correctAnswers = $answers->where('is_correct', true)->count();
                $totalAnswers = $answers->count();
                
                if ($totalAnswers > 0) {
                    $scorePercentage = round(($correctAnswers / $totalAnswers) * 100);
                    $updates['score'] = $scorePercentage;
                    $updates['passed'] = $scorePercentage >= $attempt->quiz->passing_score;
                }
                $needsFix = true;
            }
            
            if ($needsFix) {
                $attempt->update($updates);
                $fixed++;
                
                $this->line("✅ Tentative {$attempt->id} corrigée:");
                foreach ($updates as $field => $value) {
                    $this->line("   {$field}: {$value}");
                }
            }
        }
        
        $this->info("🎉 {$fixed} tentatives corrigées sur {$attempts->count()} analysées");
        
        return 0;
    }
}
