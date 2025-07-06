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
                'name' => 'Premier Code',
                'description' => 'Avoir soumis son premier exercice.',
                'icon' => 'fa-play-circle',
                'type' => 'achievement',
                'requirements' => json_encode([
                    ['type' => 'first_exercise', 'value' => 1]
                ]),
                'points_reward' => 50,
                'color' => '#1EA38B',
                'is_locked' => false,
                'unlock_order' => 1
            ],
            [
                'name' => 'HTML Expert',
                'description' => 'Avoir complété le parcours HTML/CSS.',
                'icon' => 'fa-html5',
                'type' => 'achievement',
                'requirements' => json_encode([
                    ['type' => 'html_css_completed', 'value' => 1]
                ]),
                'points_reward' => 100,
                'color' => '#FF8E2A',
                'is_locked' => false,
                'unlock_order' => 2
            ],
            [
                'name' => 'JS Ninja',
                'description' => 'Maîtriser les concepts avancés de JavaScript.',
                'icon' => 'fa-js-square',
                'type' => 'achievement',
                'requirements' => json_encode([
                    ['type' => 'js_advanced', 'value' => 1]
                ]),
                'points_reward' => 150,
                'color' => '#E32D31',
                'is_locked' => false,
                'unlock_order' => 3
            ],
            [
                'name' => 'Database Guru',
                'description' => 'Terminer le module PHP/MySQL.',
                'icon' => 'fa-database',
                'type' => 'achievement',
                'requirements' => json_encode([
                    ['type' => 'php_mysql_completed', 'value' => 1]
                ]),
                'points_reward' => 200,
                'color' => '#27B371',
                'is_locked' => true,
                'unlock_order' => 4
            ],
            [
                'name' => 'React Rockstar',
                'description' => 'Compléter le parcours ReactJS.',
                'icon' => 'fa-react',
                'type' => 'achievement',
                'requirements' => json_encode([
                    ['type' => 'react_completed', 'value' => 1]
                ]),
                'points_reward' => 250,
                'color' => '#9B59B6',
                'is_locked' => true,
                'unlock_order' => 5
            ],
            [
                'name' => 'Full-Stack Dev',
                'description' => 'Finir le parcours Full-Stack.',
                'icon' => 'fa-layer-group',
                'type' => 'achievement',
                'requirements' => json_encode([
                    ['type' => 'fullstack_completed', 'value' => 1]
                ]),
                'points_reward' => 500,
                'color' => '#FFD700',
                'is_locked' => true,
                'unlock_order' => 6
            ],
            [
                'name' => 'Serial Challenger',
                'description' => 'Avoir complété 10 défis.',
                'icon' => 'fa-fire',
                'type' => 'special',
                'requirements' => json_encode([
                    ['type' => 'challenges_completed', 'value' => 10]
                ]),
                'points_reward' => 300,
                'color' => '#E74C3C',
                'is_locked' => false,
                'unlock_order' => 7
            ],
            [
                'name' => 'Top Contributor',
                'description' => 'Avoir aidé activement sur le forum.',
                'icon' => 'fa-users',
                'type' => 'special',
                'requirements' => json_encode([
                    ['type' => 'forum_posts', 'value' => 50]
                ]),
                'points_reward' => 400,
                'color' => '#3498DB',
                'is_locked' => true,
                'unlock_order' => 8
            ]
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
} 