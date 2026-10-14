<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class PromotionalCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques cours existants pour les mettre en promotion
        $courses = Course::where('status', 'published')
            ->where('price', '>', 0)
            ->take(5)
            ->get();

        foreach ($courses as $index => $course) {
            $discountPercentage = [20, 30, 25, 35, 15][$index % 5];
            $originalPrice = $course->price;
            $discountedPrice = $originalPrice * (1 - $discountPercentage / 100);

            $course->update([
                'is_promotional' => true,
                'discount_percentage' => $discountPercentage,
                'original_price' => $originalPrice,
                'price' => round($discountedPrice, 2),
                'promotion_start_date' => now()->subDays(rand(1, 10)),
                'promotion_end_date' => now()->addDays(rand(15, 30))
            ]);

            $this->command->info("Cours '{$course->title}' mis en promotion avec {$discountPercentage}% de réduction");
        }

        $this->command->info('✅ Cours promotionnels créés avec succès !');
    }
}