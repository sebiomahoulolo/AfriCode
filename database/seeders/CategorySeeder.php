<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Développement Web',
                'description' => 'Apprenez à créer des sites web et applications web modernes avec les technologies les plus demandées.',
            ],
            [
                'name' => 'Développement Mobile',
                'description' => 'Développez des applications mobiles pour Android et iOS avec React Native, Flutter et d\'autres frameworks.',
            ],
            [
                'name' => 'Intelligence Artificielle',
                'description' => 'Explorez le monde de l\'IA, du machine learning et du deep learning avec des applications pratiques.',
            ],
            [
                'name' => 'Design UI/UX',
                'description' => 'Maîtrisez les principes du design d\'interface utilisateur et de l\'expérience utilisateur.',
            ],
            [
                'name' => 'Base de Données',
                'description' => 'Apprenez à concevoir, implémenter et optimiser des bases de données SQL et NoSQL.',
            ],
            [
                'name' => 'DevOps et Cloud',
                'description' => 'Découvrez les pratiques DevOps et le déploiement d\'applications dans le cloud.',
            ],
            [
                'name' => 'Cybersécurité',
                'description' => 'Protégez vos applications et systèmes contre les vulnérabilités et attaques informatiques.',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Analysez et visualisez des données complexes pour en extraire des informations précieuses.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
