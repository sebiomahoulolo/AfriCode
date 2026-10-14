<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Answer;

class FixExamQuestions extends Command
{
    protected $signature = 'quiz:fix-exam-questions';
    protected $description = 'Corrige les questions de l\'examen final qui n\'ont pas de réponse correcte';

    public function handle()
    {
        $this->info('🔧 Correction des questions de l\'examen final...');

        // Question 82: .px-5 est la bonne réponse (padding horizontal)
        $answer82 = Answer::where('question_id', 82)->where('text', '.px-5')->first();
        if ($answer82) {
            $answer82->update(['is_correct' => true]);
            $this->info('✅ Question 82: .px-5 marquée comme correcte');
        } else {
            $this->warn('⚠️ Question 82: Réponse .px-5 non trouvée');
        }

        // Question 83: La deuxième option est correcte (point de rupture)
        $answer83 = Answer::where('question_id', 83)->where('text', 'like', '%point de rupture%')->first();
        if ($answer83) {
            $answer83->update(['is_correct' => true]);
            $this->info('✅ Question 83: Réponse correcte marquée');
        } else {
            $this->warn('⚠️ Question 83: Réponse correcte non trouvée');
        }

        // Question 84: --bs-primary est la bonne réponse
        $answer84 = Answer::where('question_id', 84)->where('text', '--bs-primary')->first();
        if ($answer84) {
            $answer84->update(['is_correct' => true]);
            $this->info('✅ Question 84: --bs-primary marquée comme correcte');
        } else {
            $this->warn('⚠️ Question 84: Réponse --bs-primary non trouvée');
        }

        $this->info('🎉 Correction terminée');
        
        return 0;
    }
}
