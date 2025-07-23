<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les classements de base
        $leaderboards = [
            [
                'name' => 'Classement Global',
                'type' => 'global',
                'competition_id' => null,
                'start_date' => now()->subYear(),
                'end_date' => null,
                'is_active' => true,
                'settings' => json_encode([
                    'points_per_challenge' => 10,
                    'points_per_badge' => 50,
                    'points_per_achievement' => 100
                ])
            ],
            [
                'name' => 'Classement Hebdomadaire',
                'type' => 'weekly',
                'competition_id' => null,
                'start_date' => now()->startOfWeek(),
                'end_date' => now()->endOfWeek(),
                'is_active' => true,
                'settings' => json_encode([
                    'points_per_challenge' => 15,
                    'points_per_badge' => 75,
                    'points_per_achievement' => 150
                ])
            ],
            [
                'name' => 'Classement Mensuel',
                'type' => 'monthly',
                'competition_id' => null,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'is_active' => true,
                'settings' => json_encode([
                    'points_per_challenge' => 20,
                    'points_per_badge' => 100,
                    'points_per_achievement' => 200
                ])
            ]
        ];

        foreach ($leaderboards as $leaderboard) {
            DB::table('leaderboards')->insert($leaderboard);
        }

        // Créer des scores d'exemple pour les utilisateurs (si des utilisateurs existent)
        $users = DB::table('users')->take(10)->get();
        $leaderboardIds = DB::table('leaderboards')->pluck('id');

        foreach ($users as $user) {
            foreach ($leaderboardIds as $leaderboardId) {
                $score = rand(100, 2000);
                $rank = rand(1, 50);
                
                DB::table('user_scores')->insert([
                    'user_id' => $user->id,
                    'leaderboard_id' => $leaderboardId,
                    'score' => $score,
                    'rank' => $rank,
                    'score_breakdown' => json_encode([
                        'challenges_completed' => rand(5, 50),
                        'badges_earned' => rand(1, 10),
                        'achievements_unlocked' => rand(0, 5)
                    ]),
                    'last_updated' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
