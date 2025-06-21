<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Exécute les seeds de la base de données.
     */
    public function run()
    {
        $badges = [
            [
                'name' => 'Premier Cours',
                'description' => 'Compléter votre premier cours',
                'icon' => 'first-course.png',
                'type' => 'achievement',
                'requirements' => [
                    ['type' => 'courses_completed', 'value' => 1]
                ],
                'points_reward' => 100
            ],
            [
                'name' => 'Étudiant Assidu',
                'description' => 'Compléter 5 cours',
                'icon' => 'diligent-student.png',
                'type' => 'achievement',
                'requirements' => [
                    ['type' => 'courses_completed', 'value' => 5]
                ],
                'points_reward' => 500
            ],
            [
                'name' => 'Maître des Quiz',
                'description' => 'Obtenir 100% à 3 quiz',
                'icon' => 'quiz-master.png',
                'type' => 'achievement',
                'requirements' => [
                    ['type' => 'perfect_quizzes', 'value' => 3]
                ],
                'points_reward' => 300
            ],
            [
                'name' => 'Niveau 10',
                'description' => 'Atteindre le niveau 10',
                'icon' => 'level-10.png',
                'type' => 'level',
                'requirements' => [
                    ['type' => 'level', 'value' => 10]
                ],
                'points_reward' => 1000
            ],
            [
                'name' => 'Niveau 25',
                'description' => 'Atteindre le niveau 25',
                'icon' => 'level-25.png',
                'type' => 'level',
                'requirements' => [
                    ['type' => 'level', 'value' => 25]
                ],
                'points_reward' => 2500
            ],
            [
                'name' => 'Niveau 50',
                'description' => 'Atteindre le niveau 50',
                'icon' => 'level-50.png',
                'type' => 'level',
                'requirements' => [
                    ['type' => 'level', 'value' => 50]
                ],
                'points_reward' => 5000
            ],
            [
                'name' => 'Streak de 7 jours',
                'description' => 'Se connecter 7 jours consécutifs',
                'icon' => 'streak-7.png',
                'type' => 'special',
                'requirements' => [
                    ['type' => 'streak_days', 'value' => 7]
                ],
                'points_reward' => 200
            ],
            [
                'name' => 'Streak de 30 jours',
                'description' => 'Se connecter 30 jours consécutifs',
                'icon' => 'streak-30.png',
                'type' => 'special',
                'requirements' => [
                    ['type' => 'streak_days', 'value' => 30]
                ],
                'points_reward' => 1000
            ]
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
} 