<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques formateurs et catégories aléatoires
        $formateurs = User::where('role', 'formateur')->orWhere('role', 'admin')->get();
        $categories = Category::all();
        
        if ($formateurs->isEmpty()) {
            // Créer un formateur par défaut si aucun n'existe
            $formateur = User::create([
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'formateur@africode.com',
                'password' => bcrypt('password'),
                'role' => 'formateur',
                'email_verified_at' => now(),
            ]);
            $formateurs = collect([$formateur]);
        }
        
        if ($categories->isEmpty()) {
            // Créer une catégorie par défaut si aucune n'existe
            $category = Category::create([
                'name' => 'Développement Web',
                'slug' => 'developpement-web',
                'description' => 'Cours de développement web et technologies associées',
            ]);
            $categories = collect([$category]);
        }
        
        // Tableau de cours à créer
        $coursesData = [
            [
                'title' => 'Introduction à Laravel',
                'short_description' => 'Apprenez les bases du framework PHP Laravel',
                'full_description' => '<p>Laravel est un framework PHP élégant et expressif qui simplifie le développement web en facilitant les tâches courantes utilisées dans la plupart des projets, comme l\'authentification, le routage, les sessions et la mise en cache.</p><p>Dans ce cours, vous apprendrez à construire des applications web robustes avec Laravel, en utilisant ses fonctionnalités puissantes comme Eloquent ORM, Blade templating, et bien plus encore.</p>',
                'level' => 'debutant',
                'price' => 49.99,
                'currency' => 'EUR',
                'status' => 'published',
                'learning_objectives' => [
                    'Comprendre l\'architecture MVC de Laravel',
                    'Maîtriser le système de routage de Laravel',
                    'Créer des modèles avec Eloquent ORM',
                    'Développer des vues avec le moteur de template Blade',
                    'Implémenter l\'authentification des utilisateurs',
                    'Gérer les migrations et les seeds de base de données',
                    'Créer des API RESTful avec Laravel',
                ],
                'prerequisites' => [
                    'Connaissances de base en PHP',
                    'Familiarité avec les concepts de programmation orientée objet',
                    'Compréhension de base du modèle MVC',
                    'Connaissances de base en HTML, CSS et JavaScript',
                ],
                'faq' => [
                    [
                        'question' => 'Ce cours est-il adapté aux débutants en PHP?',
                        'answer' => 'Oui, mais il est recommandé d\'avoir des connaissances de base en PHP et en programmation orientée objet pour tirer le meilleur parti de ce cours.'
                    ],
                    [
                        'question' => 'Quelle version de Laravel est utilisée dans ce cours?',
                        'answer' => 'Ce cours utilise Laravel 10, la dernière version stable du framework.'
                    ],
                    [
                        'question' => 'Quels sont les projets pratiques inclus dans ce cours?',
                        'answer' => 'Vous développerez un système de blog, une API RESTful et une application de gestion de tâches tout au long du cours.'
                    ],
                    [
                        'question' => 'Puis-je obtenir de l\'aide si je suis bloqué?',
                        'answer' => 'Oui, vous pouvez poser vos questions dans la section commentaires de chaque leçon et obtenir de l\'aide de l\'instructeur ou de la communauté.'
                    ],
                ],
                'testimonials' => [
                    [
                        'name' => 'Amadou Diallo',
                        'avatar' => 'https://randomuser.me/api/portraits/men/22.jpg',
                        'rating' => 5,
                        'date' => Carbon::now()->subDays(15),
                        'comment' => 'Ce cours m\'a permis de comprendre rapidement Laravel. Les explications sont claires et les projets pratiques très utiles.'
                    ],
                    [
                        'name' => 'Fatou Sow',
                        'avatar' => 'https://randomuser.me/api/portraits/women/28.jpg',
                        'rating' => 5,
                        'date' => Carbon::now()->subDays(30),
                        'comment' => 'Excellente formation! J\'ai pu développer ma première application professionnelle en suivant ce cours. Je recommande vivement.'
                    ],
                    [
                        'name' => 'Moussa Camara',
                        'avatar' => 'https://randomuser.me/api/portraits/men/45.jpg',
                        'rating' => 4,
                        'date' => Carbon::now()->subDays(45),
                        'comment' => 'Très bon cours avec des explications détaillées. J\'aurais aimé plus d\'exercices pratiques, mais dans l\'ensemble c\'est excellent.'
                    ],
                ],
            ],
            [
                'title' => 'Développement Mobile avec Flutter',
                'short_description' => 'Créez des applications mobiles multiplateformes avec Flutter',
                'full_description' => '<p>Flutter est un framework de développement d\'applications mobiles créé par Google qui permet de créer des applications mobiles multiplateformes avec un seul code base.</p><p>Dans ce cours, vous apprendrez à développer des applications pour iOS et Android en utilisant Flutter et le langage Dart. Vous découvrirez comment créer des interfaces utilisateur élégantes et réactives, gérer l\'état de votre application et bien plus encore.</p>',
                'level' => 'intermediaire',
                'price' => 59.99,
                'currency' => 'EUR',
                'status' => 'published',
                'learning_objectives' => [
                    'Comprendre les concepts fondamentaux de Flutter et Dart',
                    'Créer des interfaces utilisateur avec les widgets Flutter',
                    'Gérer l\'état de l\'application avec différentes approches',
                    'Intégrer des services REST API dans vos applications',
                    'Implémenter le stockage local et la gestion des données',
                    'Publier vos applications sur Google Play et App Store',
                    'Utiliser Firebase pour l\'authentification et la base de données',
                ],
                'prerequisites' => [
                    'Aucune expérience préalable en développement mobile n\'est requise',
                    'Connaissance de base d\'un langage de programmation (JavaScript, Java, etc.)',
                    'Ordinateur avec Flutter SDK installé (Windows, macOS ou Linux)',
                    'Pour le déploiement iOS: ordinateur macOS (optionnel)',
                ],
                'faq' => [
                    [
                        'question' => 'Dois-je avoir un Mac pour développer des applications iOS avec Flutter?',
                        'answer' => 'Pour le développement, non, mais pour publier sur l\'App Store, vous aurez besoin d\'un Mac pour générer le fichier IPA.'
                    ],
                    [
                        'question' => 'Flutter est-il difficile à apprendre?',
                        'answer' => 'Non, Flutter est considéré comme relativement facile à apprendre, surtout si vous avez déjà des connaissances en programmation. Le langage Dart est intuitif et la documentation est excellente.'
                    ],
                    [
                        'question' => 'Les applications Flutter sont-elles aussi performantes que les applications natives?',
                        'answer' => 'Oui, les applications Flutter sont compilées en code natif et offrent des performances comparables aux applications développées nativement.'
                    ],
                    [
                        'question' => 'Quelle application allons-nous construire dans ce cours?',
                        'answer' => 'Nous allons développer une application de e-commerce complète avec authentification, panier d\'achat, paiements et notifications push.'
                    ],
                ],
                'testimonials' => [
                    [
                        'name' => 'Kofi Mensah',
                        'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
                        'rating' => 5,
                        'date' => Carbon::now()->subDays(10),
                        'comment' => 'Flutter est incroyable et ce cours est le meilleur moyen de l\'apprendre. J\'ai pu publier mon application en seulement 2 mois après avoir suivi ce cours.'
                    ],
                    [
                        'name' => 'Aminata Touré',
                        'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                        'rating' => 5,
                        'date' => Carbon::now()->subDays(25),
                        'comment' => 'Excellente ressource pour apprendre Flutter! Les projets sont bien pensés et permettent de comprendre toutes les facettes du framework.'
                    ],
                    [
                        'name' => 'Seydou Bah',
                        'avatar' => 'https://randomuser.me/api/portraits/men/56.jpg',
                        'rating' => 4,
                        'date' => Carbon::now()->subDays(40),
                        'comment' => 'Très bonne formation, détaillée et complète. L\'instructeur répond rapidement aux questions et les projets sont intéressants.'
                    ],
                ],
            ],
            [
                'title' => 'Machine Learning avec Python',
                'short_description' => 'Initiez-vous au Machine Learning et à l\'Intelligence Artificielle',
                'full_description' => '<p>Le Machine Learning (apprentissage automatique) est un domaine en plein essor qui permet aux ordinateurs d\'apprendre à partir de données sans être explicitement programmés.</p><p>Ce cours vous introduit aux concepts fondamentaux du Machine Learning et vous montre comment utiliser Python et ses bibliothèques populaires comme Scikit-learn, TensorFlow et Keras pour développer des modèles prédictifs et résoudre des problèmes complexes.</p>',
                'level' => 'avance',
                'price' => 69.99,
                'currency' => 'EUR',
                'status' => 'published',
                'learning_objectives' => [
                    'Comprendre les concepts fondamentaux du Machine Learning',
                    'Maîtriser les algorithmes de régression et de classification',
                    'Préparer et nettoyer des données pour l\'entraînement de modèles',
                    'Construire des réseaux de neurones avec TensorFlow et Keras',
                    'Évaluer et améliorer les performances des modèles',
                    'Appliquer le Machine Learning à des problèmes concrets',
                    'Déployer des modèles ML dans des applications web',
                ],
                'prerequisites' => [
                    'Connaissances de base en programmation Python',
                    'Notions de mathématiques (algèbre linéaire, probabilités et statistiques)',
                    'Compréhension des concepts de base en science des données',
                    'Ordinateur avec Python 3.7+ installé',
                ],
                'faq' => [
                    [
                        'question' => 'Dois-je être un expert en mathématiques pour ce cours?',
                        'answer' => 'Non, nous expliquons les concepts mathématiques nécessaires de manière intuitive, mais des connaissances de base en algèbre, statistiques et probabilités sont utiles.'
                    ],
                    [
                        'question' => 'Est-ce que ce cours couvre l\'apprentissage profond (Deep Learning)?',
                        'answer' => 'Oui, le cours commence par les fondamentaux du Machine Learning puis progresse vers l\'apprentissage profond avec TensorFlow et Keras.'
                    ],
                    [
                        'question' => 'Ai-je besoin d\'un GPU puissant pour suivre ce cours?',
                        'answer' => 'Pour la plupart des exercices, un CPU standard est suffisant. Pour les parties plus avancées sur le Deep Learning, nous vous montrons comment utiliser Google Colab (gratuit) avec des GPU.'
                    ],
                    [
                        'question' => 'Quels projets allons-nous réaliser durant ce cours?',
                        'answer' => 'Vous développerez plusieurs projets, incluant un système de recommandation, un classificateur d\'images, un modèle de prédiction de prix et un chatbot basique.'
                    ],
                ],
                'testimonials' => [
                    [
                        'name' => 'Omar Sy',
                        'avatar' => 'https://randomuser.me/api/portraits/men/76.jpg',
                        'rating' => 5,
                        'date' => Carbon::now()->subDays(20),
                        'comment' => 'Ce cours m\'a donné une solide compréhension du Machine Learning. Les concepts complexes sont expliqués de manière simple et accessible.'
                    ],
                    [
                        'name' => 'Awa Diop',
                        'avatar' => 'https://randomuser.me/api/portraits/women/54.jpg',
                        'rating' => 5,
                        'date' => Carbon::now()->subDays(35),
                        'comment' => 'Formation complète et bien structurée. J\'ai pu appliquer ces connaissances dans mon travail dès la fin du cours. Hautement recommandé!'
                    ],
                    [
                        'name' => 'Kwame Nkrumah',
                        'avatar' => 'https://randomuser.me/api/portraits/men/62.jpg',
                        'rating' => 4,
                        'date' => Carbon::now()->subDays(50),
                        'comment' => 'Excellente introduction au ML. J\'aurais aimé plus de détails sur certains algorithmes avancés, mais c\'est une base solide pour commencer.'
                    ],
                ],
            ],
        ];
        
        // Création des cours
        foreach ($coursesData as $courseData) {
            $course = new Course();
            $course->title = $courseData['title'];
            $course->slug = Str::slug($courseData['title']);
            $course->short_description = $courseData['short_description'];
            $course->full_description = $courseData['full_description'];
            $course->learning_objectives = $courseData['learning_objectives'];
            $course->prerequisites = $courseData['prerequisites'];
            $course->faq = $courseData['faq'];
            $course->testimonials = $courseData['testimonials'];
            $course->level = $courseData['level'];
            $course->price = $courseData['price'];
            $course->currency = $courseData['currency'];
            $course->status = $courseData['status'];
            
            // Sélection aléatoire d'un formateur et d'une catégorie
            $course->formateur_id = $formateurs->random()->id;
            $course->category_id = $categories->random()->id;
            
            // Date de publication (par défaut aujourd'hui)
            $course->published_at = Carbon::now();
            
            $course->save();
        }
    }
}
