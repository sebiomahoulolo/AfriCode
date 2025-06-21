<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = Module::all();
        
        if ($modules->isEmpty()) {
            $this->command->error('Aucun module trouvé. Veuillez d\'abord exécuter ModuleSeeder.');
            return;
        }
        
        // Pour chaque module, créer plusieurs leçons
        foreach ($modules as $module) {
            // Déterminer le nombre de leçons en fonction de la position du module
            $courseName = $module->course->title ?? '';
            $courseSlug = $module->course->slug ?? '';
            
            // Cas spéciaux pour certains modules spécifiques
            if ($courseSlug === 'html-css-pour-debutants' && $module->order === 1) {
                $this->createHTMLIntroLessons($module);
            } 
            elseif ($courseSlug === 'html-css-pour-debutants' && $module->order === 2) {
                $this->createHTMLFundamentalsLessons($module);
            }
            elseif ($courseSlug === 'react-js-le-guide-complet' && $module->order === 1) {
                $this->createReactIntroLessons($module);
            }
            else {
                // Pour les autres modules, créer des leçons génériques
                $numLessons = rand(3, 7); // Entre 3 et 7 leçons par module
                
                for ($i = 1; $i <= $numLessons; $i++) {
                    $contentType = $this->getRandomContentType();
                    $isPreviewable = ($i === 1 && rand(0, 1) === 1); // Première leçon a 50% de chance d'être prévisualisable
                    
                    Lesson::create([
                        'module_id' => $module->id,
                        'title' => "Leçon {$i}: " . $this->getLessonTitle($i, $numLessons),
                        'content_type' => $contentType,
                        'video_url' => $contentType === 'video' ? 'https://www.youtube.com/watch?v=example-' . rand(1000, 9999) : null,
                        'text_content' => $contentType === 'text' ? $this->generateLoremIpsum() : null,
                        'pdf_path' => $contentType === 'pdf' ? 'lessons/pdf/lesson-' . rand(100, 999) . '.pdf' : null,
                        'external_url' => $contentType === 'external' ? 'https://example.com/resources/' . rand(1000, 9999) : null,
                        'duration_minutes' => rand(5, 45),
                        'order' => $i,
                        'is_previewable' => $isPreviewable,
                    ]);
                }
            }
        }
    }
    
    /**
     * Crée des leçons spécifiques pour le module d'introduction à HTML/CSS
     */
    private function createHTMLIntroLessons($module)
    {
        $lessons = [
            [
                'title' => 'Bienvenue au cours HTML & CSS',
                'content_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=intro-html-css',
                'duration_minutes' => 10,
                'is_previewable' => true,
            ],
            [
                'title' => 'Comment fonctionne le Web',
                'content_type' => 'text',
                'text_content' => 'Le Web est un ensemble de technologies qui permettent de consulter des pages via Internet. Il repose sur le protocole HTTP qui permet d\'échanger des documents entre un navigateur et un serveur. Dans cette leçon, nous explorons les concepts fondamentaux qui sous-tendent le fonctionnement du Web moderne.',
                'duration_minutes' => 15,
                'is_previewable' => true,
            ],
            [
                'title' => 'Les outils du développeur Web',
                'content_type' => 'text',
                'text_content' => 'Pour développer des sites web, vous aurez besoin de plusieurs outils : un éditeur de code (comme VS Code, Sublime Text ou Atom), un navigateur moderne (Chrome, Firefox, etc.) et ses outils de développement, et éventuellement un environnement de développement local.',
                'duration_minutes' => 20,
                'is_previewable' => false,
            ],
            [
                'title' => 'Structure d\'une page web',
                'content_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=structure-page-web',
                'duration_minutes' => 25,
                'is_previewable' => false,
            ],
            [
                'title' => 'Quiz: Concepts fondamentaux',
                'content_type' => 'quiz_link',
                'external_url' => 'https://africode.com/quizzes/html-basics',
                'duration_minutes' => 15,
                'is_previewable' => false,
            ],
        ];
        
        $order = 1;
        foreach ($lessons as $lessonData) {
            Lesson::create([
                'module_id' => $module->id,
                'title' => $lessonData['title'],
                'content_type' => $lessonData['content_type'],
                'video_url' => $lessonData['content_type'] === 'video' ? $lessonData['video_url'] : null,
                'text_content' => $lessonData['content_type'] === 'text' ? $lessonData['text_content'] : null,
                'external_url' => $lessonData['content_type'] === 'quiz_link' || $lessonData['content_type'] === 'external' ? $lessonData['external_url'] : null,
                'duration_minutes' => $lessonData['duration_minutes'],
                'order' => $order++,
                'is_previewable' => $lessonData['is_previewable'],
            ]);
        }
    }
    
    /**
     * Crée des leçons spécifiques pour le module des fondamentaux HTML
     */
    private function createHTMLFundamentalsLessons($module)
    {
        $lessons = [
            [
                'title' => 'Structure de base en HTML5',
                'content_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=html5-structure',
                'duration_minutes' => 18,
                'is_previewable' => false,
            ],
            [
                'title' => 'Les balises essentielles',
                'content_type' => 'text',
                'text_content' => 'Dans cette leçon, nous explorerons les balises HTML les plus couramment utilisées : h1-h6, p, a, img, ul, ol, li, div, span, et bien d\'autres. Vous apprendrez à les utiliser correctement pour structurer votre contenu.',
                'duration_minutes' => 25,
                'is_previewable' => false,
            ],
            [
                'title' => 'Formulaires HTML',
                'content_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=html-forms',
                'duration_minutes' => 30,
                'is_previewable' => false,
            ],
            [
                'title' => 'HTML sémantique',
                'content_type' => 'text',
                'text_content' => 'Le HTML sémantique consiste à utiliser des balises qui décrivent clairement leur contenu et leur fonction. Avec HTML5, nous disposons de balises comme <header>, <footer>, <nav>, <article>, <section> et bien d\'autres qui donnent un sens à la structure de votre document.',
                'duration_minutes' => 22,
                'is_previewable' => false,
            ],
            [
                'title' => 'Exercice pratique: Créer une page de profil',
                'content_type' => 'pdf',
                'pdf_path' => 'lessons/pdf/exercice-html-profil.pdf',
                'duration_minutes' => 45,
                'is_previewable' => false,
            ],
            [
                'title' => 'Quiz: Maîtrise de HTML',
                'content_type' => 'quiz_link',
                'external_url' => 'https://africode.com/quizzes/html-mastery',
                'duration_minutes' => 15,
                'is_previewable' => false,
            ],
        ];
        
        $order = 1;
        foreach ($lessons as $lessonData) {
            Lesson::create([
                'module_id' => $module->id,
                'title' => $lessonData['title'],
                'content_type' => $lessonData['content_type'],
                'video_url' => $lessonData['content_type'] === 'video' ? $lessonData['video_url'] : null,
                'text_content' => $lessonData['content_type'] === 'text' ? $lessonData['text_content'] : null,
                'pdf_path' => $lessonData['content_type'] === 'pdf' ? $lessonData['pdf_path'] : null,
                'external_url' => $lessonData['content_type'] === 'quiz_link' || $lessonData['content_type'] === 'external' ? $lessonData['external_url'] : null,
                'duration_minutes' => $lessonData['duration_minutes'],
                'order' => $order++,
                'is_previewable' => $lessonData['is_previewable'],
            ]);
        }
    }
    
    /**
     * Crée des leçons spécifiques pour le module d'introduction à React
     */
    private function createReactIntroLessons($module)
    {
        $lessons = [
            [
                'title' => 'Introduction à React et son écosystème',
                'content_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=intro-to-react',
                'duration_minutes' => 20,
                'is_previewable' => true,
            ],
            [
                'title' => 'Configuration de l\'environnement de développement',
                'content_type' => 'text',
                'text_content' => 'Dans cette leçon, nous allons configurer notre environnement de développement pour travailler avec React. Nous installerons Node.js, npm, et créerons notre première application avec Create React App.',
                'duration_minutes' => 30,
                'is_previewable' => false,
            ],
            [
                'title' => 'JavaScript ES6+ pour React',
                'content_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=es6-for-react',
                'duration_minutes' => 35,
                'is_previewable' => false,
            ],
            [
                'title' => 'Alternatives à Create React App',
                'content_type' => 'text',
                'text_content' => 'Bien que Create React App soit un excellent point de départ, il existe d\'autres outils comme Vite, Next.js ou Gatsby qui peuvent être plus adaptés à certains projets. Dans cette leçon, nous explorerons ces alternatives.',
                'duration_minutes' => 25,
                'is_previewable' => false,
            ],
            [
                'title' => 'Quiz: Les bases de React',
                'content_type' => 'quiz_link',
                'external_url' => 'https://africode.com/quizzes/react-basics',
                'duration_minutes' => 15,
                'is_previewable' => false,
            ],
        ];
        
        $order = 1;
        foreach ($lessons as $lessonData) {
            Lesson::create([
                'module_id' => $module->id,
                'title' => $lessonData['title'],
                'content_type' => $lessonData['content_type'],
                'video_url' => $lessonData['content_type'] === 'video' ? $lessonData['video_url'] : null,
                'text_content' => $lessonData['content_type'] === 'text' ? $lessonData['text_content'] : null,
                'external_url' => $lessonData['content_type'] === 'quiz_link' || $lessonData['content_type'] === 'external' ? $lessonData['external_url'] : null,
                'duration_minutes' => $lessonData['duration_minutes'],
                'order' => $order++,
                'is_previewable' => $lessonData['is_previewable'],
            ]);
        }
    }
    
    /**
     * Get a generic lesson title based on its position.
     */
    private function getLessonTitle($position, $total)
    {
        if ($position === 1) {
            return 'Introduction au sujet';
        } elseif ($position === $total) {
            return 'Récapitulatif et exercice pratique';
        } else {
            $topics = [
                'Concepts clés',
                'Application pratique',
                'Étude de cas',
                'Techniques avancées',
                'Résolution de problèmes courants',
                'Bonnes pratiques',
                'Optimisation',
                'Intégration avec d\'autres outils',
                'Exercices guidés',
                'Démonstration en direct',
            ];
            
            return $topics[array_rand($topics)];
        }
    }
    
    /**
     * Get a random content type for the lesson.
     */
    private function getRandomContentType()
    {
        $types = [
            'video' => 40,
            'text' => 30,
            'pdf' => 15,
            'quiz_link' => 10,
            'external' => 5 // 5% chance
        ];
        
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($types as $type => $chance) {
            $cumulative += $chance;
            if ($rand <= $cumulative) {
                return $type;
            }
        }
        
        return 'text'; // fallback
    }
    
    /**
     * Generate lorem ipsum text for lesson content.
     */
    private function generateLoremIpsum()
    {
        $paragraphs = [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam venenatis felis in justo sagittis, vel gravida erat facilisis. Duis fermentum augue vitae risus luctus, in finibus sem condimentum. Proin tincidunt, nibh at fermentum condimentum, urna metus semper arcu, eu pellentesque ipsum justo vel lectus.",
            
            "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.",
            
            "At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.",
            
            "Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae."
        ];
        
        // Return between 1 and 3 random paragraphs
        $numParagraphs = rand(1, 3);
        $selectedParagraphs = array_slice($paragraphs, 0, $numParagraphs);
        
        return implode("\n\n", $selectedParagraphs);
    }
}
