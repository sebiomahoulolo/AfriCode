<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenges = [
            [
                'name' => 'Défi Quotidien : Fonction Inverse',
                'description' => 'Écrire une fonction JavaScript qui inverse une chaîne de caractères. Créez une fonction qui prend une chaîne en paramètre et retourne cette chaîne inversée.',
                'type' => 'daily',
                'difficulty' => 'debutant',
                'time_limit_minutes' => 30,
                'requirements' => json_encode([
                    'language' => 'javascript',
                    'function_name' => 'reverseString',
                    'test_cases' => [
                        ['input' => 'hello', 'output' => 'olleh'],
                        ['input' => 'world', 'output' => 'dlrow']
                    ]
                ]),
                'rewards' => json_encode([
                    'points' => 10,
                    'experience' => 50,
                    'badge' => null
                ]),
                'start_date' => now(),
                'end_date' => now()->addDay(),
                'is_active' => true,
                'is_featured' => true,
                'hints' => json_encode([
                    'Utilisez la méthode split() pour convertir la chaîne en tableau',
                    'Utilisez reverse() pour inverser le tableau',
                    'Utilisez join() pour reconvertir en chaîne'
                ]),
                'solution_template' => 'function reverseString(str) {\n    // Votre code ici\n}'
            ],
            [
                'name' => 'Défi Hebdomadaire : API Météo',
                'description' => 'Créer une page web simple qui affiche la météo d\'une ville en utilisant une API publique. Utilisez l\'API OpenWeatherMap ou une alternative gratuite.',
                'type' => 'weekly',
                'difficulty' => 'intermediaire',
                'time_limit_minutes' => 120,
                'requirements' => json_encode([
                    'technologies' => ['html', 'css', 'javascript'],
                    'features' => [
                        'Champ de saisie pour la ville',
                        'Affichage de la température',
                        'Affichage de la description météo',
                        'Gestion des erreurs'
                    ]
                ]),
                'rewards' => json_encode([
                    'points' => 50,
                    'experience' => 200,
                    'badge' => 'api_master'
                ]),
                'start_date' => now()->startOfWeek(),
                'end_date' => now()->endOfWeek(),
                'is_active' => true,
                'is_featured' => true,
                'hints' => json_encode([
                    'Utilisez fetch() pour les appels API',
                    'Gérez les erreurs avec try/catch',
                    'Utilisez des icônes pour améliorer l\'interface'
                ]),
                'solution_template' => '// HTML\n<input type="text" id="city">\n<button onclick="getWeather()">Météo</button>\n<div id="result"></div>\n\n// JavaScript\nasync function getWeather() {\n    // Votre code ici\n}'
            ],
            [
                'name' => 'Challenge CSS : Bouton Animé',
                'description' => 'Recréer un effet de survol complexe sur un bouton avec CSS. Le bouton doit avoir une animation fluide au survol avec changement de couleur, ombre et transformation.',
                'type' => 'daily',
                'difficulty' => 'debutant',
                'time_limit_minutes' => 45,
                'requirements' => json_encode([
                    'technologies' => ['html', 'css'],
                    'effects' => [
                        'Changement de couleur au survol',
                        'Animation d\'ombre',
                        'Transformation (scale ou translate)',
                        'Transition fluide'
                    ]
                ]),
                'rewards' => json_encode([
                    'points' => 15,
                    'experience' => 75,
                    'badge' => null
                ]),
                'start_date' => now(),
                'end_date' => now()->addDay(),
                'is_active' => true,
                'is_featured' => true,
                'hints' => json_encode([
                    'Utilisez :hover pour le survol',
                    'Utilisez transition pour les animations',
                    'Expérimentez avec transform et box-shadow'
                ]),
                'solution_template' => '.animated-button {\n    /* Votre CSS ici */\n}\n\n.animated-button:hover {\n    /* Effets au survol */\n}'
            ],
            [
                'name' => 'Challenge Backend : Authentification',
                'description' => 'Mettre en place un système d\'inscription et de connexion basique en Laravel. Créez les routes, contrôleurs et vues nécessaires.',
                'type' => 'monthly',
                'difficulty' => 'avance',
                'time_limit_minutes' => 240,
                'requirements' => json_encode([
                    'framework' => 'laravel',
                    'features' => [
                        'Formulaire d\'inscription',
                        'Formulaire de connexion',
                        'Validation des données',
                        'Hachage des mots de passe',
                        'Messages d\'erreur/succès'
                    ]
                ]),
                'rewards' => json_encode([
                    'points' => 100,
                    'experience' => 500,
                    'badge' => 'backend_master'
                ]),
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'is_active' => true,
                'is_featured' => true,
                'hints' => json_encode([
                    'Utilisez php artisan make:controller',
                    'Utilisez Hash::make() pour le mot de passe',
                    'Utilisez Auth::attempt() pour la connexion',
                    'N\'oubliez pas la validation'
                ]),
                'solution_template' => '// Routes\nRoute::post(\'/register\', [AuthController::class, \'register\']);\nRoute::post(\'/login\', [AuthController::class, \'login\']);\n\n// Contrôleur\nclass AuthController extends Controller\n{\n    // Votre code ici\n}'
            ],
            [
                'name' => 'Défi React : Todo List',
                'description' => 'Créer une application Todo List avec React. L\'application doit permettre d\'ajouter, supprimer et marquer comme terminées les tâches.',
                'type' => 'weekly',
                'difficulty' => 'intermediaire',
                'time_limit_minutes' => 180,
                'requirements' => json_encode([
                    'framework' => 'react',
                    'features' => [
                        'Ajouter une tâche',
                        'Supprimer une tâche',
                        'Marquer comme terminée',
                        'Persistance locale (localStorage)',
                        'Interface responsive'
                    ]
                ]),
                'rewards' => json_encode([
                    'points' => 75,
                    'experience' => 300,
                    'badge' => 'react_developer'
                ]),
                'start_date' => now()->startOfWeek(),
                'end_date' => now()->endOfWeek(),
                'is_active' => true,
                'is_featured' => false,
                'hints' => json_encode([
                    'Utilisez useState pour gérer l\'état',
                    'Utilisez useEffect pour la persistance',
                    'Pensez à la structure des données'
                ]),
                'solution_template' => 'function TodoList() {\n    const [todos, setTodos] = useState([]);\n    \n    // Votre code ici\n    \n    return (\n        <div>\n            {/* Votre JSX ici */}\n        </div>\n    );\n}'
            ],
            [
                'name' => 'Challenge Algorithmique : Tri de Tableau',
                'description' => 'Implémenter l\'algorithme de tri à bulles (bubble sort) en JavaScript. Créez une fonction qui trie un tableau de nombres dans l\'ordre croissant.',
                'type' => 'daily',
                'difficulty' => 'debutant',
                'time_limit_minutes' => 60,
                'requirements' => json_encode([
                    'language' => 'javascript',
                    'algorithm' => 'bubble_sort',
                    'test_cases' => [
                        ['input' => [3, 1, 4, 1, 5], 'output' => [1, 1, 3, 4, 5]],
                        ['input' => [9, 8, 7, 6, 5], 'output' => [5, 6, 7, 8, 9]]
                    ]
                ]),
                'rewards' => json_encode([
                    'points' => 20,
                    'experience' => 100,
                    'badge' => null
                ]),
                'start_date' => now(),
                'end_date' => now()->addDay(),
                'is_active' => true,
                'is_featured' => false,
                'hints' => json_encode([
                    'Utilisez deux boucles imbriquées',
                    'Comparez les éléments adjacents',
                    'Échangez si nécessaire'
                ]),
                'solution_template' => 'function bubbleSort(arr) {\n    // Votre code ici\n    return arr;\n}'
            ]
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
