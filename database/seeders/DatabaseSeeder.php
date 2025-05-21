<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel des seeders dans l'ordre approprié
        $this->call([
            CategorySeeder::class, // D'abord les catégories
            CourseSeeder::class,   // Ensuite les cours qui dépendent des catégories
        ]);
    }
}
