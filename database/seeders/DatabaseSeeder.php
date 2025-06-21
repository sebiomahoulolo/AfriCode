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
            CourseSeeder::class,
            
            // Structure des cours
            ModuleSeeder::class,
            LessonSeeder::class,
            ResourceSeeder::class,
            
            // Quiz et évaluations
            QuizSeeder::class,
            
            // Interactions des utilisateurs
            EnrollmentSeeder::class,
            
            // Autres entités  
            CompetitionSeeder::class,
            MentorshipSeeder::class,
            CertificationSeeder::class,
            BadgeSeeder::class,
            RewardSeeder::class,
        ]);
    }
}
