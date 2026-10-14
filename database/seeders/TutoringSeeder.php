<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\TutoringSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nettoyer les données existantes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('tutoring_sessions')->truncate();
        DB::table('tutors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Récupérer les formateurs
        $formateurs = User::where('role', 'formateur')->get();
        $apprenants = User::where('role', 'apprenant')->get();

        if ($formateurs->isEmpty() || $apprenants->isEmpty()) {
            $this->command->warn('Pas assez d\'utilisateurs pour créer des tuteurs. Assurez-vous d\'avoir des formateurs et des apprenants.');
            return;
        }

        $expertiseAreas = [
            ['Développement Web Frontend', 'JavaScript', 'React', 'Vue.js'],
            ['Développement Web Backend', 'PHP', 'Laravel', 'Node.js'],
            ['Développement Mobile', 'React Native', 'Flutter', 'Swift'],
            ['Data Science', 'Python', 'R', 'Machine Learning'],
            ['DevOps', 'Docker', 'Kubernetes', 'AWS'],
            ['Cybersécurité', 'Ethical Hacking', 'Network Security', 'Penetration Testing'],
            ['UI/UX Design', 'Figma', 'Adobe XD', 'User Research'],
            ['Gestion de Projet', 'Agile', 'Scrum', 'Project Management']
        ];

        $languages = [
            ['Français', 'Anglais'],
            ['Français', 'Anglais', 'Espagnol'],
            ['Français', 'Anglais', 'Arabe'],
            ['Français', 'Anglais', 'Allemand']
        ];

        $tutors = [];

        // Créer des profils de tuteurs pour chaque formateur
        foreach ($formateurs as $formateur) {
            $expertise = $expertiseAreas[array_rand($expertiseAreas)];
            $langs = $languages[array_rand($languages)];
            $hourlyRate = rand(15, 50) * 100; // Entre 1500 et 5000 FCFA

            $tutors[] = [
                'user_id' => $formateur->id,
                'bio' => "Tuteur expérimenté spécialisé en " . implode(', ', $expertise) . ". Passionné par l'enseignement et l'accompagnement des apprenants.",
                'expertise_areas' => json_encode($expertise),
                'languages' => json_encode($langs),
                'hourly_rate' => $hourlyRate,
                'is_available' => rand(0, 1),
                'availability_schedule' => json_encode([
                    'monday' => ['09:00-12:00', '14:00-18:00'],
                    'tuesday' => ['09:00-12:00', '14:00-18:00'],
                    'wednesday' => ['09:00-12:00', '14:00-18:00'],
                    'thursday' => ['09:00-12:00', '14:00-18:00'],
                    'friday' => ['09:00-12:00', '14:00-18:00'],
                    'saturday' => ['10:00-15:00'],
                    'sunday' => []
                ]),
                'rating' => rand(3, 5),
                'total_sessions' => rand(0, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insérer les tuteurs
        Tutor::insert($tutors);

        // Créer des sessions de tutorat
        $sessions = [];
        $sessionTypes = ['one_on_one', 'group', 'workshop'];
        $sessionStatuses = ['scheduled', 'in_progress', 'completed', 'cancelled'];

        $tutorIds = Tutor::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $tutorId = $tutorIds[array_rand($tutorIds)];
            $student = $apprenants->random();
            $type = $sessionTypes[array_rand($sessionTypes)];
            $status = $sessionStatuses[array_rand($sessionStatuses)];
            
            $startTime = now()->addDays(rand(-30, 30))->addHours(rand(9, 18));
            $duration = [30, 60, 90, 120][array_rand([30, 60, 90, 120])];
            $endTime = $startTime->copy()->addMinutes($duration);
            
            $tutor = Tutor::find($tutorId);
            $price = $tutor->hourly_rate * ($duration / 60);

            $sessions[] = [
                'tutor_id' => $tutorId,
                'student_id' => $student->id,
                'title' => $this->generateSessionTitle($type),
                'description' => $this->generateSessionDescription($type),
                'status' => $status,
                'type' => $type,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_minutes' => $duration,
                'price' => $price,
                'meeting_link' => $status === 'scheduled' ? 'https://meet.google.com/abc-defg-hij' : null,
                'notes' => $status === 'completed' ? 'Session terminée avec succès. L\'apprenant a bien progressé.' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insérer les sessions
        TutoringSession::insert($sessions);

        $this->command->info('Création de ' . count($tutors) . ' tuteurs et ' . count($sessions) . ' sessions de tutorat.');
    }

    private function generateSessionTitle($type)
    {
        $titles = [
            'one_on_one' => [
                'Session de mentorat personnalisée',
                'Accompagnement individuel',
                'Tutorat personnalisé',
                'Session de coaching'
            ],
            'group' => [
                'Session de groupe - Projet collaboratif',
                'Tutorat collectif - Révision',
                'Session de groupe - Exercices pratiques',
                'Atelier collaboratif'
            ],
            'workshop' => [
                'Atelier - Introduction aux concepts',
                'Workshop - Projet pratique',
                'Atelier - Bonnes pratiques',
                'Workshop - Cas d\'étude'
            ]
        ];

        return $titles[$type][array_rand($titles[$type])];
    }

    private function generateSessionDescription($type)
    {
        $descriptions = [
            'one_on_one' => 'Session de mentorat individuelle pour un accompagnement personnalisé et adapté aux besoins spécifiques de l\'apprenant.',
            'group' => 'Session de tutorat en groupe permettant l\'apprentissage collaboratif et l\'échange entre apprenants.',
            'workshop' => 'Atelier pratique axé sur l\'application concrète des concepts théoriques avec des exercices et des projets.'
        ];

        return $descriptions[$type];
    }
}
