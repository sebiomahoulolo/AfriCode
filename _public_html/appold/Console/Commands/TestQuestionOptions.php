<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;

class TestQuestionOptions extends Command
{
    protected $signature = 'test:question-options';
    protected $description = 'Teste la relation options des questions';

    public function handle()
    {
        $this->info('🔍 Test de la relation options des questions...');

        $question = Question::with('options')->first();
        
        if ($question) {
            $this->info("📝 Question: {$question->text}");
            $this->info("📊 Options: {$question->options->count()}");
            
            foreach ($question->options as $option) {
                $correct = $option->is_correct ? 'YES' : 'NO';
                $this->line("  - {$option->text} (correct: {$correct})");
            }
        } else {
            $this->error('❌ Aucune question trouvée');
        }
        
        return 0;
    }
}
