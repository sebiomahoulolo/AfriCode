<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizAttempt;
use App\Models\UserQuizAnswer;

class CheckQuizAttemptDetails extends Command
{
    protected $signature = 'quiz:check-details {--user-id=} {--course-id=}';
    protected $description = 'Vérifie les détails des tentatives de quiz';

    public function handle()
    {
        $userId = $this->option('user-id');
        $courseId = $this->option('course-id');

        $this->info('🔍 Vérification des détails des tentatives de quiz...');

        $query = QuizAttempt::with(['quiz', 'user']);
        
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
        
        foreach ($attempts as $attempt) {
            $this->info("📝 Tentative {$attempt->id} - Quiz: {$attempt->quiz->title}");
            $this->line("  Utilisateur: {$attempt->user->first_name} {$attempt->user->last_name}");
            $this->line("  Score: {$attempt->score}%");
            $this->line("  Status: {$attempt->status}");
            $this->line("  Passed: " . ($attempt->passed ? 'YES' : 'NO'));
            $this->line("  Started at: {$attempt->started_at}");
            $this->line("  Submitted at: " . ($attempt->submitted_at ? $attempt->submitted_at : 'NULL'));
            $this->line("  Completed at: " . ($attempt->completed_at ? $attempt->completed_at : 'NULL'));
            $this->line("  Passing score: {$attempt->quiz->passing_score}%");
            
            // Vérifier les réponses
            $answers = UserQuizAnswer::where('quiz_attempt_id', $attempt->id)->get();
            $this->line("  Réponses: {$answers->count()}");
            
            foreach ($answers as $answer) {
                $this->line("    Question {$answer->question_id}: " . ($answer->is_correct ? 'CORRECT' : 'INCORRECT'));
            }
            
            // Vérifier si la tentative devrait être réussie
            $shouldPass = $attempt->score >= $attempt->quiz->passing_score;
            $this->line("  Devrait être réussi: " . ($shouldPass ? 'YES' : 'NO'));
            
            // Vérifier la méthode isPassedByUser
            $isPassedByUser = $attempt->quiz->isPassedByUser($attempt->user_id);
            $this->line("  isPassedByUser(): " . ($isPassedByUser ? 'YES' : 'NO'));
            
            $this->newLine();
        }
        
        return 0;
    }
}
