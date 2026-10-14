<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupOrphanedEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:cleanup-orphaned {--hours=24 : Nombre d\'heures pour considérer une inscription comme orpheline}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les inscriptions en attente orphelines et leurs paiements échoués associés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = now()->subHours($hours);
        
        $this->info("Nettoyage des inscriptions orphelines créées avant {$cutoffTime->format('Y-m-d H:i:s')}...");
        
        // Trouver les inscriptions en attente sans paiements récents
        $orphanedEnrollments = Enrollment::whereNull('enrolled_at')
            ->where('created_at', '<', $cutoffTime)
            ->whereDoesntHave('payments', function($query) use ($cutoffTime) {
                $query->where('created_at', '>', $cutoffTime)
                      ->where('status', '!=', 'failed');
            })
            ->get();

        $cleanedCount = 0;
        
        foreach ($orphanedEnrollments as $enrollment) {
            // Supprimer les paiements échoués associés
            $deletedPayments = $enrollment->payments()->where('status', 'failed')->delete();
            
            // Supprimer l'inscription orpheline
            $enrollment->delete();
            
            $cleanedCount++;
            $this->line("Nettoyé l'inscription ID: {$enrollment->id} (paiements supprimés: {$deletedPayments})");
        }

        // Nettoyer aussi les paiements échoués orphelins
        $orphanedPayments = Payment::where('status', 'failed')
            ->where('created_at', '<', $cutoffTime)
            ->whereDoesntHave('enrollment')
            ->delete();

        $this->info("Nettoyage terminé:");
        $this->info("- Inscriptions orphelines supprimées: {$cleanedCount}");
        $this->info("- Paiements échoués orphelins supprimés: {$orphanedPayments}");
        
        Log::info('Nettoyage automatique des inscriptions orphelines', [
            'enrollments_cleaned' => $cleanedCount,
            'payments_cleaned' => $orphanedPayments,
            'cutoff_time' => $cutoffTime
        ]);
        
        return 0;
    }
}
