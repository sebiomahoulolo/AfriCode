<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompetitionTestCase;

class CompetitionTestCaseSeeder extends Seeder
{
    public function run()
    {
        // Exemple : pour la compétition d'id 1
        CompetitionTestCase::create([
            'competition_id' => 1,
            'input' => "2 3",
            'expected_output' => "5",
        ]);
        CompetitionTestCase::create([
            'competition_id' => 1,
            'input' => "10 20",
            'expected_output' => "30",
        ]);
        CompetitionTestCase::create([
            'competition_id' => 1,
            'input' => "0 0",
            'expected_output' => "0",
        ]);
    }
} 