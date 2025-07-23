<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On suppose qu'il y a déjà des utilisateurs dans la base
        $organizer = User::inRandomOrder()->first();

        Competition::create([
            'title' => 'Compétition de Programmation',
            'slug' => Str::slug('Compétition de Programmation-' . uniqid()),
            'description' => 'Participez à notre grande compétition de programmation et tentez de gagner des prix !',
            'rules' => '1. Pas de triche. 2. Respectez les délais. 3. Soyez fair-play.',
            'start_datetime' => Carbon::now()->addDays(7),
            'end_datetime' => Carbon::now()->addDays(14),
            'registration_deadline' => Carbon::now()->addDays(6),
            'level_required' => 1,
            'organizer_id' => $organizer ? $organizer->id : null,
            'status' => 'upcoming',
            'cover_image_path' => null,
            'max_participants' => 100,
            'entry_fee' => 0,
            'prizes' => json_encode(['1er prix : 100€', '2ème prix : 50€', '3ème prix : 25€']),
            'judging_criteria' => json_encode(['Originalité', 'Performance', 'Qualité du code']),
            'is_featured' => true,
            'views_count' => 0,
        ]);
    }
}
