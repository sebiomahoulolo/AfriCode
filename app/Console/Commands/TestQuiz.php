<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;

class TestQuiz extends Command
{
    protected $signature = 'test:quiz {quiz-id}';
    protected $description = 'Teste un quiz spécifique';

    public function handle()
    {
        $quizId = $this->argument('quiz-id');
        
        $this->info("🔍 Test du quiz ID: {$quizId}");

        $quiz = Quiz::with('questions.options')->find($quizId);
        
        if ($quiz) {
            $this->info("📝 Quiz: {$quiz->title}");
            $this->info("📊 Questions: {$quiz->questions->count()}");
            
            foreach ($quiz->questions as $question) {
                $this->line("  Question {$question->id}: {$question->text}");
                $this->line("    Options: {$question->options->count()}");
                
                foreach ($question->options as $option) {
                    $correct = $option->is_correct ? 'YES' : 'NO';
                    $this->line("      - {$option->text} (correct: {$correct})");
                }
            }
        } else {
            $this->error('❌ Quiz non trouvé');
        }
        
        return 0;
    }
}
