<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;

class CheckQuestionTypes extends Command
{
    protected $signature = 'quiz:check-types {quiz-id}';
    protected $description = 'Vérifie les types de questions d\'un quiz';

    public function handle()
    {
        $quizId = $this->argument('quiz-id');
        
        $this->info("🔍 Vérification des types de questions du quiz {$quizId}");

        $questions = Question::where('quiz_id', $quizId)->get();
        
        foreach ($questions as $question) {
            $this->line("Question {$question->id}: Type = {$question->type}");
        }
        
        return 0;
    }
}
