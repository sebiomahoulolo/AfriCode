<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On ne nettoie que les tables que ce seeder remplit
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Answer::truncate();
        Question::truncate();
        Quiz::truncate();
        LessonCompletion::truncate();
        Lesson::truncate();
        Module::truncate();
        Enrollment::whereIn('course_id', Course::pluck('id'))->delete();
        Course::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $formateur = User::where('role', 'formateur')->first();
        if (!$formateur) {
            $this->command->warn('Aucun formateur trouvé. Création d\'un formateur de test.');
            $formateur = User::factory()->create(['role' => 'formateur']);
        }
        
        $this->command->info('Création de 4 cours de démonstration...');
        
        $courseTitles = [
            'Développement Web Avancé avec Laravel',
            'Les Secrets de JavaScript Moderne (ES6+)',
            'Maîtriser Docker pour le Déploiement',
            'Introduction au Machine Learning avec Python'
        ];
        
        $courses = collect();
        foreach($courseTitles as $title) {
            $courses->push($this->createFullCourse($title, $formateur));
        }
        
        $this->command->info('Inscription des apprenants et simulation de la progression...');

        $apprenants = User::where('role', 'apprenant')->get();
        if ($apprenants->isEmpty()) {
            $this->command->warn('Aucun apprenant trouvé. Création de 5 apprenants de test.');
            $apprenants = User::factory()->count(5)->create(['role' => 'apprenant']);
        }

        foreach ($apprenants as $apprenant) {
            // Inscrire chaque apprenant à 3 cours aléatoires
            $coursesToEnroll = $courses->random(3);
            
            foreach ($coursesToEnroll as $course) {
                $enrollment = Enrollment::create([
                    'user_id' => $apprenant->id,
                    'course_id' => $course->id,
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                    'progress_percentage' => 0,
                ]);

                // Simuler une progression en complétant quelques leçons
                $firstModule = $course->modules()->orderBy('order')->first();
                if ($firstModule && $firstModule->lessons->isNotEmpty()) {
                    $lessonsToComplete = $firstModule->lessons->random(rand(1, $firstModule->lessons->count()));
                    foreach ($lessonsToComplete as $lesson) {
                        LessonCompletion::create([
                            'user_id' => $apprenant->id,
                            'lesson_id' => $lesson->id,
                            'enrollment_id' => $enrollment->id,
                            'completed_at' => now(),
                        ]);
                    }
                    // Mettre à jour le pourcentage de progression
                    $totalLessons = $course->getLessonsCount();
                    $completedCount = $lessonsToComplete->count();
                    $enrollment->progress_percentage = round(($completedCount / $totalLessons) * 100);
                    $enrollment->save();
                }
            }
        }

        $this->command->info('Seeder de contenu de cours exécuté avec succès !');
    }

    private function createFullCourse(string $title, User $formateur): Course
    {
        $course = Course::create([
            'title' => $title,
            'short_description' => 'Description courte pour ' . $title,
            'level' => ['Débutant', 'Intermédiaire', 'Avancé'][rand(0, 2)],
            'status' => 'published',
            'formateur_id' => $formateur->id,
            'category_id' => 1,
            'published_at' => now(),
        ]);

        // Modules
        $module1 = $course->modules()->create(['title' => 'Module 1: Introduction', 'order' => 1]);
        $module1->lessons()->createMany([
            ['title' => 'Leçon 1.1: Concepts de base', 'order' => 1, 'duration_minutes' => 15, 'text_content' => 'Contenu...'],
            ['title' => 'Leçon 1.2: Environnement', 'order' => 2, 'duration_minutes' => 20, 'text_content' => 'Contenu...'],
        ]);
        $this->addQuestionsToQuiz($this->createQuizFor($module1, ['title' => 'Quiz du Module 1', 'is_required' => true, 'passing_score' => 70]), 3);

        $module2 = $course->modules()->create(['title' => 'Module 2: Techniques Avancées', 'order' => 2]);
        $module2->lessons()->createMany([
            ['title' => 'Leçon 2.1: Technique A', 'order' => 1, 'duration_minutes' => 25, 'text_content' => 'Contenu...'],
            ['title' => 'Leçon 2.2: Technique B', 'order' => 2, 'duration_minutes' => 30, 'text_content' => 'Contenu...'],
        ]);
        $this->addQuestionsToQuiz($this->createQuizFor($module2, ['title' => 'Quiz du Module 2', 'is_required' => true, 'passing_score' => 75]), 3);

        // Quiz Final
        $this->addQuestionsToQuiz($this->createQuizFor($course, ['title' => 'Examen Final: ' . $title, 'is_required' => true, 'passing_score' => 80]), 10);
        
        return $course;
    }

    private function createQuizFor($model, array $data): Quiz
    {
        $quizData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? 'Testez vos connaissances.',
            'passing_score' => $data['passing_score'],
            'is_required' => $data['is_required'],
        ];

        if ($model instanceof Module) {
            $quizData['module_id'] = $model->id;
            $quizData['quiz_type'] = 'module_end';
        } elseif ($model instanceof Course) {
            $quizData['course_id'] = $model->id;
            $quizData['quiz_type'] = 'course_final';
        }

        return Quiz::create($quizData);
    }

    private function addQuestionsToQuiz(Quiz $quiz, int $count)
    {
        for ($i = 1; $i <= $count; $i++) {
            $question = $quiz->questions()->create([
                'text' => "Question {$i} pour le quiz '{$quiz->title}' ?",
                'type' => 'single_choice', 'points' => 10, 'order' => $i,
            ]);
            $question->answers()->createMany([
                ['text' => 'Réponse correcte', 'is_correct' => true],
                ['text' => 'Réponse incorrecte A', 'is_correct' => false],
                ['text' => 'Réponse incorrecte B', 'is_correct' => false],
            ]);
        }
    }
}
