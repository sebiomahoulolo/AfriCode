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
        // 🧍 Utilisateurs et catégories
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
        ]);

        // 📚 Contenu de cours : ce seeder crée des cours AVEC modules, leçons, quiz, etc.
        $this->call(CourseContentSeeder::class);

        // 🧱 Structure additionnelle : ressources, inscriptions...
        $this->call([
            ResourceSeeder::class,
            EnrollmentSeeder::class,
        ]);

        // 🏆 Fonctionnalités avancées
        $this->call([
            CompetitionSeeder::class,
            MentorshipSeeder::class,
            CertificationSeeder::class,
            BadgeSeeder::class,
            RewardSeeder::class,
        ]);

        // 💬 Forum apprenant
        $this->call(ForumPostSeeder::class);
    }
}
