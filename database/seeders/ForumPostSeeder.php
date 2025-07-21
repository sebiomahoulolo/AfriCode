<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumPost;
use App\Models\User;
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

        foreach ($users as $user) {
            ForumPost::create([
                'user_id' => $user->id,
                'title' => 'Sujet du forum pour ' . $user->name,
                'content' => 'Ceci est un message d\'exemple posté par ' . $user->name . ' sur le forum apprenant.',
                'slug' => Str::slug('Sujet du forum pour ' . $user->name . '-' . uniqid()),
            ]);
        }
    }
} 