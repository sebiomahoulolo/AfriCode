<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\Question;

class TestQuizEvaluationIncorrect extends Command
{
    protected $signature = 'quiz:test-incorrect {quiz-id}';
    protected $description = 'Teste la logique d\'évaluation avec des réponses incorrectes';

    public function handle()
    {
        $quizId = $this->argument('quiz-id');
        
        $this->info("🔍 Test de l'évaluation avec réponses incorrectes - Quiz {$quizId}");

        $quiz = Quiz::with('questions.options')->find($quizId);
        
        if (!$quiz) {
            $this->error('❌ Quiz non trouvé');
            return 1;
        }

        $this->info("📝 Quiz: {$quiz->title}");
        $this->newLine();

        $totalPoints = 0;
        $earnedPoints = 0;
        $correctAnswersCount = 0;

        foreach ($quiz->questions as $question) {
            $this->line("Question {$question->id}: {$question->text}");
            $this->line("Type: {$question->type}");
            
            $totalPoints += $question->points;
            
            // Simuler des réponses incorrectes (première option incorrecte)
            $incorrectAnswers = $question->options->where('is_correct', false)->pluck('id')->toArray();
            $correctAnswers = $question->options->where('is_correct', true)->pluck('id')->toArray();
            
            $this->line("Réponses correctes: " . implode(', ', $correctAnswers));
            $this->line("Réponses incorrectes: " . implode(', ', $incorrectAnswers));
            
            // Simuler la sélection de la première réponse incorrecte
            $userAnswerIds = [$incorrectAnswers[0]];
            $this->line("Réponse utilisateur: " . implode(', ', $userAnswerIds));
            
            $isCorrect = false;
            
            if ($question->type === 'single_choice') {
                $correctAnswer = $question->options->where('is_correct', true)->first();
                $isCorrect = $correctAnswer && in_array($correctAnswer->id, $userAnswerIds);
            } elseif ($question->type === 'multiple_choice') {
                $correctAnswers = $question->options->where('is_correct', true)->pluck('id')->toArray();
                
                // Si il n'y a qu'une seule réponse correcte, traiter comme single_choice
                if (count($correctAnswers) === 1) {
                    $isCorrect = in_array($correctAnswers[0], $userAnswerIds);
                } else {
                    // Vraie logique multiple_choice : toutes les bonnes réponses et aucune mauvaise
                    $isCorrect = count($correctAnswers) === count($userAnswerIds) && 
                               empty(array_diff($correctAnswers, $userAnswerIds));
                }
            } elseif ($question->type === 'true_false') {
                $correctAnswer = $question->options->where('is_correct', true)->first();
                $isCorrect = $correctAnswer && in_array($correctAnswer->id, $userAnswerIds);
            }
            
            $this->line("Résultat: " . ($isCorrect ? '✅ CORRECT' : '❌ INCORRECT'));
            
            if ($isCorrect) {
                $earnedPoints += $question->points;
                $correctAnswersCount++;
            }
            
            $this->newLine();
        }
        
        // Calculer le score
        $scorePercentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
        $passed = $scorePercentage >= $quiz->passing_score;
        
        $this->info("📊 Résultats:");
        $this->line("  Points totaux: {$totalPoints}");
        $this->line("  Points obtenus: {$earnedPoints}");
        $this->line("  Score: {$scorePercentage}%");
        $this->line("  Score de passage requis: {$quiz->passing_score}%");
        $this->line("  Résultat: " . ($passed ? '✅ RÉUSSI' : '❌ ÉCHOUÉ'));
        
        return 0;
    }
}
