<?php

namespace App\Console\Commands;

use App\Jobs\SendNewCourseEmails;
use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendCourseEmailCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:send-campaign 
                            {course_id : L\'ID du cours à promouvoir}
                            {--force : Forcer l\'envoi même si déjà envoyé}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer une campagne d\'email pour promouvoir un nouveau cours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $courseId = $this->argument('course_id');
        $force = $this->option('force');

        $course = Course::find($courseId);

        if (!$course) {
            $this->error("Cours avec l'ID {$courseId} introuvable.");
            return 1;
        }

        if ($course->status !== 'published') {
            $this->warn("Le cours '{$course->title}' n'est pas publié. Statut actuel: {$course->status}");
            
            if (!$this->confirm('Voulez-vous quand même envoyer la campagne ?')) {
                $this->info('Campagne annulée.');
                return 0;
            }
        }

        // Vérifier si une campagne a déjà été envoyée
        $campaignKey = "course_campaign_sent_{$course->id}";
        
        if (!$force && \Illuminate\Support\Facades\Cache::has($campaignKey)) {
            $this->warn("Une campagne a déjà été envoyée pour ce cours.");
            
            if (!$this->confirm('Voulez-vous forcer l\'envoi ?')) {
                $this->info('Campagne annulée. Utilisez --force pour forcer l\'envoi.');
                return 0;
            }
        }

        $this->info("Démarrage de la campagne pour le cours: {$course->title}");
        $this->info("Envoi en cours...");

        try {
            // Dispatch le job
            SendNewCourseEmails::dispatch($course);

            // Marquer que la campagne a été envoyée
            \Illuminate\Support\Facades\Cache::put($campaignKey, true, now()->addHours(24));

            $this->info("✅ Campagne d'email lancée avec succès !");
            $this->info("Les emails seront envoyés via la queue.");
            
            Log::info("Campagne email lancée manuellement", [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'user' => auth()->user()?->email ?? 'CLI',
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error("Erreur lors du lancement de la campagne: " . $e->getMessage());
            Log::error("Erreur campagne email", [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            return 1;
        }
    }
}

