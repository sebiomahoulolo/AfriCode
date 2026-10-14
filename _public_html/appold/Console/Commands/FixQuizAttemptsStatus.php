<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizAttempt;

class FixQuizAttemptsStatus extends Command
{
    protected $signature = 'quiz:fix-status {--user-id=}';
    protected $description = 'Corrige le statut des tentatives de quiz';

    public function handle()
    {
        $userId = $this->option('user-id');

        $this->info('🔧 Correction du statut des tentatives de quiz...');

        $query = QuizAttempt::with('quiz');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attempts = $query->get();
        
        $this->info("📊 Trouvé {$attempts->count()} tentatives à corriger");
        
        $fixed = 0;
        
        foreach ($attempts as $attempt) {
            $updates = [];
            
            // Corriger le statut
            if ($attempt->status !== 'completed') {
                $updates['status'] = 'completed';
            }
            
            // Corriger completed_at
            if (!$attempt->completed_at) {
                $updates['completed_at'] = $attempt->submitted_at ?: $attempt->created_at;
            }
            
            // Corriger passed
            if ($attempt->score !== null) {
                $updates['passed'] = $attempt->score >= $attempt->quiz->passing_score;
            }
            
            if (!empty($updates)) {
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
