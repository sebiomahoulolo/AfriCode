<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Bonus de Niveau 10',
                'description' => 'Récompense pour atteindre le niveau 10',
                'amount' => 50.00,
                'currency' => 'USD',
                'type' => 'level',
                'requirements' => [
                    ['type' => 'level', 'value' => 10]
                ],
                'is_active' => true
            ],
            [
                'name' => 'Bonus de Niveau 25',
                'description' => 'Récompense pour atteindre le niveau 25',
                'amount' => 100.00,
                'currency' => 'USD',
                'type' => 'level',
                'requirements' => [
                    ['type' => 'level', 'value' => 25]
                ],
                'is_active' => true
            ],
            [
                'name' => 'Bonus de Niveau 50',
                'description' => 'Récompense pour atteindre le niveau 50',
                'amount' => 250.00,
                'currency' => 'USD',
                'type' => 'level',
                'requirements' => [
                    ['type' => 'level', 'value' => 50]
                ],
                'is_active' => true
            ],
            [
                'name' => 'Récompense Quiz Parfait',
                'description' => 'Récompense pour obtenir 100% à 5 quiz',
                'amount' => 25.00,
                'currency' => 'USD',
                'type' => 'achievement',
                'requirements' => [
                    ['type' => 'perfect_quizzes', 'value' => 5]
                ],
                'is_active' => true
            ],
            [
                'name' => 'Récompense Streak Mensuel',
                'description' => 'Récompense pour maintenir un streak de 30 jours',
                'amount' => 75.00,
                'currency' => 'USD',
                'type' => 'special',
                'requirements' => [
                    ['type' => 'streak_days', 'value' => 30]
                ],
                'is_active' => true
            ]
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
