<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\Answer;

class FixQuizQuestions extends Command
{
    protected $signature = 'quiz:fix-questions {--quiz-id=}';
    protected $description = 'Corrige les questions de quiz qui n\'ont pas de réponse correcte';

    public function handle()
    {
        $quizId = $this->option('quiz-id');
        
        $this->info('🔧 Correction des questions de quiz...');

        $query = Question::with('options');
        
        if ($quizId) {
            $query->where('quiz_id', $quizId);
        }

        $questions = $query->get();
        
        $this->info("📊 Trouvé {$questions->count()} questions à analyser");
        
        $fixed = 0;
        
        foreach ($questions as $question) {
            $hasCorrectAnswer = $question->options->where('is_correct', true)->count() > 0;
            
            if (!$hasCorrectAnswer) {
                $this->warn("⚠️ Question {$question->id} n'a pas de réponse correcte:");
                $this->line("   {$question->text}");
                
                // Afficher les options
                foreach ($question->options as $option) {
                    $this->line("     - {$option->text}");
                }
                
                // Demander à l'utilisateur quelle option est correcte
                $this->newLine();
                $this->info("Options disponibles:");
                $options = $question->options->toArray();
                foreach ($options as $index => $option) {
                    $this->line("  {$index}: {$option['text']}");
                }
                
                $correctIndex = $this->ask("Quelle option est correcte? (0-" . (count($options) - 1) . ")");
                
                if (is_numeric($correctIndex) && $correctIndex >= 0 && $correctIndex < count($options)) {
                    $correctOption = $options[$correctIndex];
                    $answer = Answer::find($correctOption['id']);
                    if ($answer) {
                        $answer->update(['is_correct' => true]);
                        $fixed++;
                        $this->info("✅ Option {$correctIndex} marquée comme correcte");
                    }
                } else {
                    $this->error("❌ Index invalide");
                }
                
                $this->newLine();
            }
        }
        
        $this->info("🎉 {$fixed} questions corrigées sur {$questions->count()} analysées");
        
        return 0;
    }
}
