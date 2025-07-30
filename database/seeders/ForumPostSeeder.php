<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumPost;
use App\Models\User;
use App\Models\CourseForum;
use Illuminate\Support\Str;

class ForumPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On suppose qu'il y a déjà des utilisateurs dans la base
        $users = User::inRandomOrder()->take(5)->get();
        $courseForum = CourseForum::first();

        if (!$courseForum) {
            $this->command->warn('Aucun CourseForum trouvé. Veuillez exécuter le CourseForumSeeder ou créer un forum de cours manuellement.');
            return;
        }

        foreach ($users as $user) {
            ForumPost::create([
                'user_id' => $user->id,
                'course_forum_id' => $courseForum->id,
                'content' => 'Ceci est un message d\'exemple posté par ' . $user->name . ' sur le forum apprenant.',
            ]);
        }
    }
} 