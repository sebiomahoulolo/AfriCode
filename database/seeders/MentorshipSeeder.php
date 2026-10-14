<?php

namespace Database\Seeders;

use App\Models\Mentorship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nettoyer les mentorats existants
        DB::table('mentorships')->truncate();

        // Récupérer les formateurs et apprenants
        $formateurs = User::where('role', 'formateur')->get();
        $apprenants = User::where('role', 'apprenant')->get();

        if ($formateurs->isEmpty() || $apprenants->isEmpty()) {
            $this->command->warn('Pas assez d\'utilisateurs pour créer des mentorats. Assurez-vous d\'avoir des formateurs et des apprenants.');
            return;
        }

        $specializations = [
            'Développement Web Frontend',
            'Développement Web Backend',
            'Développement Mobile',
            'Data Science',
            'Machine Learning',
            'DevOps',
            'Cybersécurité',
            'UI/UX Design',
            'Gestion de Projet'
        ];

        $statuses = [
            'pending_mentor_approval' => 3,
            'active' => 5,
            'declined' => 1,
            'ended_by_mentor' => 2,
            'ended_by_mentee' => 1
        ];

        $mentorships = [];

        foreach ($statuses as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $formateur = $formateurs->random();
                $apprenant = $apprenants->random();
                $specialization = $specializations[array_rand($specializations)];

                // Vérifier qu'il n'y a pas déjà un mentorat entre ces deux utilisateurs
                $existing = Mentorship::where('mentor_id', $formateur->id)
                    ->where('mentee_id', $apprenant->id)
                    ->where('specialization_area', $specialization)
                    ->first();

                if (!$existing) {
                    $requestedAt = now()->subDays(rand(1, 30));
                    $approvedAt = null;
                    $endedAt = null;

                    if (in_array($status, ['active', 'ended_by_mentor', 'ended_by_mentee'])) {
                        $approvedAt = $requestedAt->copy()->addHours(rand(1, 48));
                    }

                    if (in_array($status, ['ended_by_mentor', 'ended_by_mentee'])) {
                        $endedAt = $approvedAt->copy()->addDays(rand(7, 60));
                    }

                    $mentorships[] = [
                        'mentor_id' => $formateur->id,
                        'mentee_id' => $apprenant->id,
                        'specialization_area' => $specialization,
                        'status' => $status,
                        'requested_at' => $requestedAt,
                        'approved_at' => $approvedAt,
                        'ended_at' => $endedAt,
                        'created_at' => $requestedAt,
                        'updated_at' => $endedAt ?? $approvedAt ?? $requestedAt,
                    ];
                }
            }
        }

        // Insérer les mentorats
        Mentorship::insert($mentorships);

        $this->command->info('Création de ' . count($mentorships) . ' mentorats de test.');
    }
}
