<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupPendingEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollments:cleanup-pending {--hours=24 : Nombre d\'heures après lesquelles nettoyer les inscriptions en attente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les inscriptions en attente qui n\'ont pas été payées dans les délais impartis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);
        
        $this->info("🧹 Nettoyage des inscriptions en attente depuis plus de {$hours} heures...");
        
        // Récupérer les inscriptions pour des cours payants sans paiement complété
        $pendingEnrollments = Enrollment::whereHas('course', function($query) {
                $query->where('price', '>', 0);
            })
            ->where('enrolled_at', '<', $cutoffTime)
            ->whereDoesntHave('payments', function($query) {
                $query->where('status', 'completed');
            })
            ->with(['course', 'user'])
            ->get();
            
        if ($pendingEnrollments->isEmpty()) {
            $this->info("✅ Aucune inscription en attente à nettoyer.");
            return 0;
        }
        
        $this->info("📋 {$pendingEnrollments->count()} inscription(s) en attente trouvée(s).");
        
        $cleanedCount = 0;
        $progressBar = $this->output->createProgressBar($pendingEnrollments->count());
        $progressBar->start();
        
        foreach ($pendingEnrollments as $enrollment) {
            try {
                // Vérifier s'il y a des paiements en cours pour cette inscription
                $pendingPayments = Payment::where('enrollment_id', $enrollment->id)
                    ->whereIn('status', ['pending', 'processing'])
                    ->get();
                
                // Annuler les paiements en cours
                foreach ($pendingPayments as $payment) {
                    $payment->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => 'Nettoyage automatique - Délai de paiement expiré'
                    ]);
                }
                
                // Supprimer l'inscription
                $enrollment->delete();
                $cleanedCount++;
                
            } catch (\Exception $e) {
                $this->error("❌ Erreur lors du nettoyage de l'inscription {$enrollment->id}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("✅ Nettoyage terminé !");
        $this->info("📊 Statistiques :");
        $this->info("   - Inscriptions supprimées : {$cleanedCount}");
        $this->info("   - Paiements annulés : " . Payment::where('status', 'cancelled')
            ->where('cancelled_at', '>=', Carbon::now()->subMinutes(5))->count());
        
        return 0;
    }
}
