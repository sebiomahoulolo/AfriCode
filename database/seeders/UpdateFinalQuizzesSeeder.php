<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;

class UpdateFinalQuizzesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mettre à jour tous les quiz finaux avec une limite de 3 tentatives
        Quiz::where('quiz_type', 'course_final')->update([
            'max_attempts' => 3
        ]);
    }
} 