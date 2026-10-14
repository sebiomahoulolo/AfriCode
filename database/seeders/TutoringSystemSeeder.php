<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutoringSession;
use App\Models\TutoringSessionParticipant;
use App\Models\TutoringSessionResource;
use App\Models\TutoringSessionMessage;
use App\Models\TutoringSessionMessageAttachment;
use App\Models\TutoringLearningObjective;
use Carbon\Carbon;

class TutoringSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs formateurs s'ils n'existent pas
        $formateurs = User::where('role', 'formateur')->get();
        if ($formateurs->isEmpty()) {
            $formateurs = collect([
                User::create([
                    'first_name' => 'Marie',
                    'last_name' => 'Kouassi',
                    'email' => 'marie.kouassi@africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'formateur',
                    'is_active' => true,
                    'phone' => '+225 07 12 34 56 78',
                    'bio' => 'Experte en développement web et mobile'
                ]),
                User::create([
                    'first_name' => 'Jean',
                    'last_name' => 'Traoré',
                    'email' => 'jean.traore@africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'formateur',
                    'is_active' => true,
                    'phone' => '+225 07 87 65 43 21',
                    'bio' => 'Spécialiste en intelligence artificielle'
                ]),
                User::create([
                    'first_name' => 'Fatou',
                    'last_name' => 'Diallo',
                    'email' => 'fatou.diallo@africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'formateur',
                    'is_active' => true,
                    'phone' => '+225 07 55 44 33 22',
                    'bio' => 'Experte en cybersécurité et réseaux'
                ])
            ]);
        }

        // Créer des utilisateurs apprenants s'ils n'existent pas
        $apprenants = User::where('role', 'apprenant')->get();
        if ($apprenants->isEmpty()) {
            $apprenants = collect([
                User::create([
                    'first_name' => 'Aminata',
                    'last_name' => 'Koné',
                    'email' => 'aminata.kone@student.africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'apprenant',
                    'is_active' => true,
                    'phone' => '+225 07 11 22 33 44',
                    'bio' => 'Étudiante en développement web'
                ]),
                User::create([
                    'first_name' => 'Mohamed',
                    'last_name' => 'Camara',
                    'email' => 'mohamed.camara@student.africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'apprenant',
                    'is_active' => true,
                    'phone' => '+225 07 22 33 44 55',
                    'bio' => 'Étudiant en intelligence artificielle'
                ]),
                User::create([
                    'first_name' => 'Aïcha',
                    'last_name' => 'Ouattara',
                    'email' => 'aicha.ouattara@student.africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'apprenant',
                    'is_active' => true,
                    'phone' => '+225 07 33 44 55 66',
                    'bio' => 'Étudiante en cybersécurité'
                ]),
                User::create([
                    'first_name' => 'Ibrahim',
                    'last_name' => 'Sissoko',
                    'email' => 'ibrahim.sissoko@student.africode.tech',
                    'password' => bcrypt('password'),
                    'role' => 'apprenant',
                    'is_active' => true,
                    'phone' => '+225 07 44 55 66 77',
                    'bio' => 'Étudiant en développement mobile'
                ])
            ]);
        }

        // Créer des profils tuteurs
        $tutors = [];
        foreach ($formateurs as $formateur) {
            $tutor = Tutor::firstOrCreate(
                ['user_id' => $formateur->id],
                [
                    'bio' => $formateur->bio ?? 'Formateur expérimenté sur AfriCode',
                    'expertise_areas' => $this->getExpertiseAreas($formateur->first_name . ' ' . $formateur->last_name),
                    'languages' => ['Français', 'Anglais'],
                    'hourly_rate' => rand(3000, 8000), // 3000-8000 FCFA
                    'is_available' => true,
                    'rating' => rand(40, 50) / 10, // 4.0-5.0
                    'total_sessions' => rand(10, 50)
                ]
            );
            $tutors[] = $tutor;
        }

        // Créer des sessions de tutorat
        $sessions = [];
        for ($i = 0; $i < 15; $i++) {
            $tutor = $tutors[array_rand($tutors)];
            $student = $apprenants->random();
            $startTime = Carbon::now()->addDays(rand(1, 30))->addHours(rand(9, 17));
            $duration = [60, 90, 120, 180][array_rand([60, 90, 120, 180])];
            $endTime = $startTime->copy()->addMinutes($duration);
            
            $session = TutoringSession::create([
                'tutor_id' => $tutor->id,
                'student_id' => $student->id,
                'title' => $this->getSessionTitle($tutor->expertise_areas[0] ?? 'Développement'),
                'description' => $this->getSessionDescription(),
                'status' => ['scheduled', 'completed', 'cancelled'][array_rand(['scheduled', 'completed', 'cancelled'])],
                'type' => ['one_on_one', 'group', 'workshop'][array_rand(['one_on_one', 'group', 'workshop'])],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_minutes' => $duration,
                'price' => $tutor->hourly_rate * ($duration / 60),
                'meeting_link' => 'https://meet.jit.si/africode-' . uniqid(),
                'session_code' => strtoupper(substr(md5(uniqid()), 0, 8)),
                'notes' => rand(0, 1) ? $this->getSessionNotes() : null,
                'feedback' => rand(0, 1) ? $this->getSessionFeedback() : null,
                'started_at' => rand(0, 1) ? $startTime->copy()->addMinutes(rand(0, 10)) : null,
                'completed_at' => rand(0, 1) ? $endTime->copy()->addMinutes(rand(-10, 10)) : null,
                'cancelled_at' => null,
                'cancellation_reason' => null
            ]);
            
            $sessions[] = $session;
        }

        // Ajouter des participants supplémentaires pour les sessions de groupe
        foreach ($sessions as $session) {
            if ($session->type === 'group' || $session->type === 'workshop') {
                $additionalParticipants = $apprenants->where('id', '!=', $session->student_id)->random(rand(1, 3));
                foreach ($additionalParticipants as $participant) {
                    TutoringSessionParticipant::create([
                        'session_id' => $session->id,
                        'user_id' => $participant->id,
                        'joined_at' => rand(0, 1) ? $session->start_time->copy()->addMinutes(rand(0, 15)) : null
                    ]);
                }
            }
        }

        // Ajouter des ressources aux sessions
        foreach ($sessions as $session) {
            $resourceCount = rand(1, 4);
            for ($i = 0; $i < $resourceCount; $i++) {
                TutoringSessionResource::create([
                    'session_id' => $session->id,
                    'title' => $this->getResourceTitle(),
                    'type' => ['link', 'document', 'video'][array_rand(['link', 'document', 'video'])],
                    'url' => $this->getResourceUrl(),
                    'file_path' => null,
                    'description' => $this->getResourceDescription()
                ]);
            }
        }

        // Ajouter des objectifs d'apprentissage
        foreach ($sessions as $session) {
            $objectiveCount = rand(2, 5);
            for ($i = 0; $i < $objectiveCount; $i++) {
                TutoringLearningObjective::create([
                    'session_id' => $session->id,
                    'objective' => $this->getLearningObjective()
                ]);
            }
        }

        // Ajouter des messages aux sessions
        foreach ($sessions as $session) {
            $messageCount = rand(5, 15);
            $participants = collect([$session->tutor->user, $session->student])
                ->merge($session->participants->pluck('user'));
            
            for ($i = 0; $i < $messageCount; $i++) {
                $sender = $participants->random();
                $messageTime = $session->start_time->copy()->addMinutes(rand(-30, $session->duration_minutes + 30));
                
                $messageType = rand(0, 2) === 0 ? 'text' : (rand(0, 1) === 0 ? 'system' : 'announcement');
                $message = TutoringSessionMessage::create([
                    'session_id' => $session->id,
                    'user_id' => $sender->id,
                    'message' => $this->getMessageContent(),
                    'type' => $messageType,
                    'is_system_message' => $messageType !== 'text',
                    'created_at' => $messageTime,
                    'updated_at' => $messageTime
                ]);

                // Ajouter des pièces jointes à certains messages
                if (rand(0, 3) === 0) { // 25% de chance
                    TutoringSessionMessageAttachment::create([
                        'message_id' => $message->id,
                        'filename' => $this->getAttachmentFileName(),
                        'path' => 'tutoring-attachments/' . uniqid() . '.pdf',
                        'size' => rand(100000, 5000000), // 100KB - 5MB
                        'mime_type' => 'application/pdf'
                    ]);
                }
            }
        }

        $this->command->info('Système de tutorat peuplé avec succès !');
        $this->command->info('- ' . count($tutors) . ' tuteurs créés');
        $this->command->info('- ' . count($sessions) . ' sessions créées');
        $this->command->info('- ' . TutoringSessionMessage::count() . ' messages créés');
        $this->command->info('- ' . TutoringSessionResource::count() . ' ressources créées');
        $this->command->info('- ' . TutoringLearningObjective::count() . ' objectifs créés');
    }

    private function getExpertiseAreas($name)
    {
        $areas = [
            'Marie Kouassi' => ['Développement Web', 'JavaScript', 'React', 'Node.js'],
            'Jean Traoré' => ['Intelligence Artificielle', 'Machine Learning', 'Python', 'Data Science'],
            'Fatou Diallo' => ['Cybersécurité', 'Réseaux', 'Linux', 'Ethical Hacking']
        ];
        
        return $areas[$name] ?? ['Développement Web', 'Programmation'];
    }

    private function getSessionTitle($expertise)
    {
        $titles = [
            'Développement Web' => [
                'Introduction au HTML5 et CSS3',
                'JavaScript moderne et ES6+',
                'Création d\'applications React',
                'API REST avec Node.js',
                'Responsive Design avancé'
            ],
            'Intelligence Artificielle' => [
                'Introduction au Machine Learning',
                'Algorithmes de classification',
                'Deep Learning avec TensorFlow',
                'Traitement du langage naturel',
                'Vision par ordinateur'
            ],
            'Cybersécurité' => [
                'Fondamentaux de la sécurité',
                'Tests de pénétration',
                'Sécurisation des applications web',
                'Forensique numérique',
                'Gestion des incidents de sécurité'
            ]
        ];
        
        $categoryTitles = $titles[$expertise] ?? $titles['Développement Web'];
        return $categoryTitles[array_rand($categoryTitles)];
    }

    private function getSessionDescription()
    {
        $descriptions = [
            'Session pratique pour approfondir les concepts théoriques vus en cours.',
            'Atelier interactif avec des exercices concrets et des projets pratiques.',
            'Séance de révision et de préparation aux examens avec des cas d\'étude.',
            'Tutorat personnalisé pour résoudre les difficultés spécifiques.',
            'Session de groupe pour échanger et collaborer sur des projets communs.'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    private function getSessionNotes()
    {
        $notes = [
            'Prévoir un ordinateur avec les logiciels installés.',
            'Session enregistrée pour révision ultérieure.',
            'Matériel de cours disponible sur la plateforme.',
            'Questions à préparer avant la session.',
            'Projet à terminer pour la prochaine séance.'
        ];
        
        return $notes[array_rand($notes)];
    }

    private function getSessionFeedback()
    {
        $feedbacks = [
            'Excellente session, très instructive et bien structurée.',
            'Bon rythme, les exercices étaient adaptés au niveau.',
            'Très satisfait, j\'ai beaucoup appris.',
            'Session productive, merci pour les explications claires.',
            'Parfait, je recommande vivement ce tuteur.'
        ];
        
        return $feedbacks[array_rand($feedbacks)];
    }

    private function getResourceTitle()
    {
        $titles = [
            'Guide de référence HTML5',
            'Documentation JavaScript ES6',
            'Tutoriel React complet',
            'Exercices pratiques Python',
            'Cours de cybersécurité',
            'Projet d\'exemple',
            'Liens utiles et ressources',
            'Correction des exercices',
            'Slides de présentation',
            'Code source du projet'
        ];
        
        return $titles[array_rand($titles)];
    }

    private function getResourceUrl()
    {
        $urls = [
            'https://developer.mozilla.org/fr/docs/Web/HTML',
            'https://javascript.info/',
            'https://reactjs.org/docs/getting-started.html',
            'https://docs.python.org/3/tutorial/',
            'https://owasp.org/www-project-top-ten/',
            'https://github.com/africode/examples',
            'https://stackoverflow.com/questions/tagged/javascript',
            'https://www.w3schools.com/',
            'https://codepen.io/',
            'https://jsfiddle.net/'
        ];
        
        return $urls[array_rand($urls)];
    }

    private function getResourceDescription()
    {
        $descriptions = [
            'Ressource complète pour approfondir le sujet.',
            'Documentation officielle recommandée.',
            'Exemples pratiques et cas d\'usage.',
            'Exercices supplémentaires pour s\'entraîner.',
            'Liens vers des outils utiles.'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    private function getLearningObjective()
    {
        $objectives = [
            'Comprendre les concepts fondamentaux',
            'Maîtriser la syntaxe et les bonnes pratiques',
            'Savoir implémenter des solutions concrètes',
            'Être capable de déboguer et optimiser le code',
            'Développer une approche méthodologique',
            'Acquérir de l\'autonomie dans le développement',
            'Comprendre l\'architecture des applications',
            'Savoir utiliser les outils de développement',
            'Maîtriser les tests et la qualité du code',
            'Être capable de documenter son travail'
        ];
        
        return $objectives[array_rand($objectives)];
    }

    private function getMessageContent()
    {
        $messages = [
            'Bonjour tout le monde ! Comment allez-vous ?',
            'J\'ai une question sur l\'exercice de la page 15.',
            'Merci pour cette explication très claire !',
            'Pouvez-vous répéter la dernière partie ?',
            'J\'ai terminé l\'exercice, est-ce que quelqu\'un peut vérifier ?',
            'Excellente session aujourd\'hui, merci beaucoup !',
            'J\'ai un problème avec mon code, pouvez-vous m\'aider ?',
            'Voici le lien vers le projet que nous avons discuté.',
            'Rappel : la prochaine session est prévue pour demain.',
            'N\'oubliez pas de faire les exercices pour la prochaine fois.',
            'J\'ai ajouté de nouvelles ressources dans le dossier partagé.',
            'Quelqu\'un a-t-il des questions avant qu\'on termine ?',
            'Parfait ! Tout le monde a bien compris ?',
            'Je vais vous envoyer le corrigé par email.',
            'À bientôt pour la prochaine session !'
        ];
        
        return $messages[array_rand($messages)];
    }

    private function getAttachmentFileName()
    {
        $files = [
            'exercice-corrige.pdf',
            'presentation-cours.pdf',
            'projet-exemple.zip',
            'ressources-utiles.pdf',
            'guide-pratique.pdf',
            'exercices-supplementaires.pdf',
            'documentation-technique.pdf',
            'code-source-projet.zip',
            'evaluation-formatif.pdf',
            'liens-ressources.pdf'
        ];
        
        return $files[array_rand($files)];
    }
}
