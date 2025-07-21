<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'apprenant')->get();
        $courses = Course::where('status', 'published')->get();
        
        if ($users->isEmpty()) {
            $this->command->error('Aucun apprenant trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }
        
        if ($courses->isEmpty()) {
            $this->command->error('Aucun cours publié trouvé. Veuillez d\'abord exécuter CourseSeeder.');
            return;
        }
        
        // Pour suivre les options déjà utilisées
        $processedOptions = [];
        
        // Créer des inscriptions avec différents scénarios de progression
        foreach ($users as $user) {
            // Chaque utilisateur est inscrit à 1-4 cours aléatoires
            $numCourses = rand(1, min(4, $courses->count()));
            $selectedCourses = $courses->random($numCourses);
            
            foreach ($selectedCourses as $course) {
                // Créer une clé unique pour cette combinaison utilisateur/cours
               // Ne pas insérer si une inscription existe déjà pour cet utilisateur et ce cours
if (
    Enrollment::where('user_id', $user->id)
              ->where('course_id', $course->id)
              ->exists()
) {
    continue;
}

                
                // Créer l'inscription
                $enrollmentDate = now()->subDays(rand(1, 90)); // Inscrit au cours il y a 1-90 jours
                
                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'enrolled_at' => $enrollmentDate,
                    'progress_percentage' => 0,
                ]);
                
                // Déterminer le scénario de progression pour cet utilisateur et ce cours
                $progressScenario = rand(1, 5);
                
                switch ($progressScenario) {
                    case 1:
                        // Scénario 1: Aucune progression (juste inscrit)
                        break;
                    
                    case 2:
                        // Scénario 2: Début de progression (10-30%)
                        $this->createPartialProgress($enrollment, 10, 30);
                        break;
                    
                    case 3:
                        // Scénario 3: Progression moyenne (30-70%)
                        $this->createPartialProgress($enrollment, 30, 70);
                        break;
                    
                    case 4:
                        // Scénario 4: Progression avancée (70-95%)
                        $this->createPartialProgress($enrollment, 70, 95);
                        break;
                    
                    case 5:
                        // Scénario 5: Cours terminé (100%)
                        $this->createCompleteProgress($enrollment);
                        break;
                }
                
                // Mettre à jour le pourcentage de progression
                $enrollment->updateProgress();
            }
        }
    }
    
    /**
     * Crée une progression partielle pour une inscription
     */
    private function createPartialProgress($enrollment, $minPercent, $maxPercent)
    {
        $course = $enrollment->course;
        $modules = Module::where('course_id', $course->id)
                        ->with('lessons')
                        ->orderBy('order')
                        ->get();
        
        $totalLessons = 0;
        foreach ($modules as $module) {
            $totalLessons += $module->lessons->count();
        }
        
        if ($totalLessons === 0) {
            return;
        }
        
        // Calculer combien de leçons doivent être marquées comme terminées
        $targetCompletion = rand(
            ceil($totalLessons * $minPercent / 100),
            floor($totalLessons * $maxPercent / 100)
        );
        
        $completedCount = 0;
        
        // Parcourir les modules dans l'ordre
        foreach ($modules as $module) {
            if ($completedCount >= $targetCompletion) {
                break;
            }
            
            $lessons = Lesson::where('module_id', $module->id)
                            ->orderBy('order')
                            ->get();
            
            // Compléter les leçons de ce module dans l'ordre
            foreach ($lessons as $lesson) {
                if ($completedCount >= $targetCompletion) {
                    break;
                }
                
                // Marquer la leçon comme terminée
                LessonCompletion::create([
                    'user_id' => $enrollment->user_id,
                    'lesson_id' => $lesson->id,
                    'enrollment_id' => $enrollment->id,
                    'completed_at' => now()->subDays(rand(0, 30))->addMinutes(rand(0, 1440)),
                ]);
                
                $completedCount++;
            }
        }
    }
    
    /**
     * Crée une progression complète pour une inscription
     */
    private function createCompleteProgress($enrollment)
    {
        $course = $enrollment->course;
        $modules = Module::where('course_id', $course->id)
                        ->with('lessons')
                        ->orderBy('order')
                        ->get();
        
        // Date de base pour la complétion, entre la date d'inscription et maintenant
        $baseDate = $enrollment->enrolled_at->addDays(rand(1, max(1, now()->diffInDays($enrollment->enrolled_at))));
        
        // Parcourir tous les modules et leçons dans l'ordre
        foreach ($modules as $moduleIndex => $module) {
            $lessons = Lesson::where('module_id', $module->id)
                            ->orderBy('order')
                            ->get();
            
            foreach ($lessons as $lessonIndex => $lesson) {
                // Ajouter un délai progressif entre les leçons
                $completionDate = clone $baseDate;
                $completionDate->addHours(($moduleIndex * count($lessons) + $lessonIndex) * rand(1, 24));
                
                // Si la date dépasse aujourd'hui, la ramener à une date récente
                if ($completionDate > now()) {
                    $completionDate = now()->subHours(rand(1, 48));
                }
                
                // Marquer la leçon comme terminée
                LessonCompletion::create([
                    'user_id' => $enrollment->user_id,
                    'lesson_id' => $lesson->id,
                    'enrollment_id' => $enrollment->id,
                    'completed_at' => $completionDate,
                ]);
            }
        }
        
        // Marquer le cours comme terminé
        $enrollment->completed_at = now()->subDays(rand(0, 7));
        $enrollment->save();
    }
}
