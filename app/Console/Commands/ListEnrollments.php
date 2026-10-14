<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;

class ListEnrollments extends Command
{
    protected $signature = 'list:enrollments';
    protected $description = 'Liste les inscriptions existantes';

    public function handle()
    {
        $this->info('📋 Inscriptions:');
        $enrollments = Enrollment::with(['user', 'course'])->get();
        
        if ($enrollments->isEmpty()) {
            $this->warn('Aucune inscription trouvée');
            return 0;
        }
        
        foreach ($enrollments as $enrollment) {
            $this->line("  ID: {$enrollment->id}");
            $this->line("  Utilisateur: {$enrollment->user->first_name} {$enrollment->user->last_name} (ID: {$enrollment->user->id})");
            $this->line("  Cours: {$enrollment->course->title} (ID: {$enrollment->course->id})");
            $this->line("  Status: {$enrollment->status}");
            $this->line("  Créé: {$enrollment->created_at->format('Y-m-d H:i:s')}");
            $this->newLine();
        }
        
        return 0;
    }
}
