<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\User;

class ReassignCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:reassign {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réassigne les cours d\'un formateur à un autre';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromId = $this->option('from');
        $toId = $this->option('to');
        
        if (!$fromId || !$toId) {
            $this->error('Usage: php artisan courses:reassign --from=ID_FORMATEUR_SOURCE --to=ID_FORMATEUR_DESTINATION');
            return;
        }
        
        // Vérifier que les formateurs existent
        $fromFormateur = User::where('id', $fromId)->where('role', 'formateur')->first();
        $toFormateur = User::where('id', $toId)->where('role', 'formateur')->first();
        
        if (!$fromFormateur) {
            $this->error("Formateur source (ID: $fromId) non trouvé ou n'est pas un formateur");
            return;
        }
        
        if (!$toFormateur) {
            $this->error("Formateur destination (ID: $toId) non trouvé ou n'est pas un formateur");
            return;
        }
        
        $this->info("Réassignation des cours de {$fromFormateur->first_name} {$fromFormateur->last_name} vers {$toFormateur->first_name} {$toFormateur->last_name}");
        
        // Récupérer les cours du formateur source
        $courses = Course::where('formateur_id', $fromId)->get();
        
        if ($courses->count() == 0) {
            $this->warn("Aucun cours trouvé pour le formateur source");
            return;
        }
        
        $this->info("Cours trouvés: " . $courses->count());
        
        // Réassigner les cours
        $updated = Course::where('formateur_id', $fromId)->update(['formateur_id' => $toId]);
        
        $this->info("Cours réassignés: $updated");
        
        // Afficher les cours réassignés
        $reassignedCourses = Course::where('formateur_id', $toId)->get();
        foreach ($reassignedCourses as $course) {
            $this->line("- {$course->title} (ID: {$course->id})");
        }
    }
}
