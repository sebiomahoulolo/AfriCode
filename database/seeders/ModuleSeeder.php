<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->command->error('Aucun cours trouvé. Veuillez d\'abord exécuter CourseSeeder.');
            return;
        }
        
        // Modules pour le cours HTML & CSS pour Débutants
        $htmlCourse = Course::where('slug', 'html-css-pour-debutants')->first();
        if ($htmlCourse) {
            $htmlModules = [
                [
                    'title' => 'Introduction au développement web',
                    'description' => 'Découvrez les bases du développement web et comment fonctionnent les navigateurs.',
                    'order' => 1,
                ],
                [
                    'title' => 'Les fondamentaux de HTML5',
                    'description' => 'Apprenez à structurer vos pages web avec les balises HTML5.',
                    'order' => 2,
                ],
                [
                    'title' => 'Les bases de CSS3',
                    'description' => 'Donnez du style à vos pages web avec CSS3.',
                    'order' => 3,
                ],
                [
                    'title' => 'Mise en page responsive',
                    'description' => 'Créez des designs qui s\'adaptent à tous les appareils.',
                    'order' => 4,
                ],
                [
                    'title' => 'Projet final: Portfolio personnel',
                    'description' => 'Mettez en pratique vos compétences pour créer un portfolio professionnel.',
                    'order' => 5,
                ],
            ];
            
            foreach ($htmlModules as $moduleData) {
                Module::create([
                    'course_id' => $htmlCourse->id,
                    'title' => $moduleData['title'],
                    'description' => $moduleData['description'],
                    'order' => $moduleData['order'],
                ]);
            }
        }
        
        // Modules pour le cours React JS
        $reactCourse = Course::where('slug', 'react-js-le-guide-complet')->first();
        if ($reactCourse) {
            $reactModules = [
                [
                    'title' => 'Introduction à React et configuration de l\'environnement',
                    'description' => 'Découvrez React et configurez votre environnement de développement.',
                    'order' => 1,
                ],
                [
                    'title' => 'Les fondamentaux de React: Composants et JSX',
                    'description' => 'Apprenez à créer des composants React et à utiliser JSX.',
                    'order' => 2,
                ],
                [
                    'title' => 'Gérer l\'état avec useState et useEffect',
                    'description' => 'Maîtrisez les hooks useState et useEffect pour gérer l\'état de vos composants.',
                    'order' => 3,
                ],
                [
                    'title' => 'Routing et navigation avec React Router',
                    'description' => 'Créez des applications multi-pages avec React Router.',
                    'order' => 4,
                ],
                [
                    'title' => 'Gestion de l\'état global avec Redux',
                    'description' => 'Apprenez à utiliser Redux pour gérer l\'état global de votre application.',
                    'order' => 5,
                ],
                [
                    'title' => 'Projet final: Application de liste de tâches',
                    'description' => 'Mettez en pratique vos compétences pour créer une application complète.',
                    'order' => 6,
                ],
            ];
            
            foreach ($reactModules as $moduleData) {
                Module::create([
                    'course_id' => $reactCourse->id,
                    'title' => $moduleData['title'],
                    'description' => $moduleData['description'],
                    'order' => $moduleData['order'],
                ]);
            }
        }
        
        // Créer des modules pour tous les autres cours
        foreach ($courses as $course) {
            // Ignorer les cours qui ont déjà des modules
            if ($course->modules()->count() > 0) {
                continue;
            }
            
            // Nombre aléatoire de modules (entre 3 et 8)
            $numModules = rand(3, 8);
            
            for ($i = 1; $i <= $numModules; $i++) {
                Module::create([
                    'course_id' => $course->id,
                    'title' => "Module {$i}: " . $this->getModuleTitle($i, $numModules),
                    'description' => "Description du module {$i} pour le cours {$course->title}.",
                    'order' => $i,
                ]);
            }
        }
    }
    
    /**
     * Get a generic module title based on its position.
     */
    private function getModuleTitle($position, $total)
    {
        if ($position === 1) {
            return 'Introduction et présentation du cours';
        } elseif ($position === $total) {
            return 'Projet final et conclusion';
        } elseif ($position === 2) {
            return 'Concepts fondamentaux';
        } elseif ($position === $total - 1) {
            return 'Concepts avancés';
        } else {
            $topics = [
                'Outils et techniques',
                'Applications pratiques',
                'Étude de cas',
                'Développement de fonctionnalités',
                'Bonnes pratiques',
                'Optimisation et performance',
                'Intégration avec d\'autres technologies',
                'Tests et débogage',
            ];
            
            return $topics[array_rand($topics)];
        }
    }
}
