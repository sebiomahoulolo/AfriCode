<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Utilisateurs, catégories et cours
            UserSeeder::class,
            CategorySeeder::class,
            // CourseSeeder::class, // Commenté car CourseContentSeeder crée déjà un cours
            
            // Structure des cours
            ModuleSeeder::class,
            LessonSeeder::class,
            ResourceSeeder::class,
            
            // Quiz et évaluations (ancien seeder, maintenant désactivé)
            // QuizSeeder::class,
            
            // Interactions des utilisateurs
            EnrollmentSeeder::class,
            
            // Autres entités  
            CompetitionSeeder::class,
            MentorshipSeeder::class,
            // CertificationSeeder::class, // Les certificats sont créés par la logique de l'app
        ]);

        // Ce seeder crée un cours complet avec modules, leçons et quiz pour les tests.
        $this->call(CourseContentSeeder::class);
    }
}
