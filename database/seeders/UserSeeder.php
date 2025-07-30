<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::withoutSyncingToSearch(function () {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@africode.com',
            'password' => Hash::make('password'),
            'role' => 'administrateur',
            'bio' => 'Administrateur de la plateforme AfriCode.',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create formateurs
        $formateurs = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@africode.com',
                'bio' => 'Expert en développement web avec plus de 10 ans d\'expérience. Passionné par l\'enseignement des technologies modernes.',
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Leclerc',
                'email' => 'marie.leclerc@africode.com',
                'bio' => 'Développeuse full-stack et UI/UX designer. Spécialiste en React et Node.js.',
            ],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Bamba',
                'email' => 'ahmed.bamba@africode.com',
                'bio' => 'Architecte logiciel et expert en bases de données. Formateur en développement backend et DevOps.',
            ],
        ];

        foreach ($formateurs as $formateur) {
            User::create([
                'first_name' => $formateur['first_name'],
                'last_name' => $formateur['last_name'],
                'email' => $formateur['email'],
                'password' => Hash::make('password'),
                'role' => 'formateur',
                'bio' => $formateur['bio'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create apprenants
        $apprenants = [
            [
                'first_name' => 'Moussa',
                'last_name' => 'Diallo',
                'email' => 'moussa.diallo@example.com',
                'bio' => 'Étudiant en informatique passionné par le développement web.',
            ],
            [
                'first_name' => 'Fatou',
                'last_name' => 'Sow',
                'email' => 'fatou.sow@example.com',
                'bio' => 'Développeuse junior cherchant à approfondir ses compétences.',
            ],
            [
                'first_name' => 'Amadou',
                'last_name' => 'Ndiaye',
                'email' => 'amadou.ndiaye@example.com',
                'bio' => 'Reconversion professionnelle dans le domaine du développement web.',
            ],
            [
                'first_name' => 'Aïcha',
                'last_name' => 'Touré',
                'email' => 'aicha.toure@example.com',
                'bio' => 'Passionnée de data science et d\'intelligence artificielle.',
            ],
            [
                'first_name' => 'Omar',
                'last_name' => 'Diop',
                'email' => 'omar.diop@example.com',
                'bio' => 'Expert en marketing digital souhaitant apprendre à coder.',
            ],
        ];

        foreach ($apprenants as $apprenant) {
            User::create([
                'first_name' => $apprenant['first_name'],
                'last_name' => $apprenant['last_name'],
                'email' => $apprenant['email'],
                'password' => Hash::make('password'),
                'role' => 'apprenant',
                'bio' => $apprenant['bio'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create more apprenants using factory
        User::factory(20)->create([
            'role' => 'apprenant',
            'email_verified_at' => now(),
        ]);
    });
    }
}
