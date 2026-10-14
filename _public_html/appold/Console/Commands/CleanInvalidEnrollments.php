<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanInvalidEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollments:clean-invalid {--dry-run : Afficher seulement ce qui serait nettoyé sans faire les modifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les inscriptions activées sans paiement réussi pour les cours payants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 Mode DRY-RUN : Aucune modification ne sera effectuée');
        }

        $this->info('🧹 Recherche des inscriptions invalides...');

        // Trouver les inscriptions activées pour des cours payants sans paiement réussi
        $invalidEnrollments = Enrollment::whereNotNull('enrolled_at')
            ->whereHas('course', function($query) {
                $query->where('price', '>', 0);
            })
            ->whereDoesntHave('payments', function($query) {
                $query->where('status', 'succeeded');
            })
            ->with(['course', 'user', 'payments'])
            ->get();

        if ($invalidEnrollments->isEmpty()) {
            $this->info('✅ Aucune inscription invalide trouvée !');
            return 0;
        }

        $this->warn("⚠️  Trouvé {$invalidEnrollments->count()} inscription(s) invalide(s) :");
        $this->newLine();

        $tableData = [];
        foreach ($invalidEnrollments as $enrollment) {
            $paymentStatuses = $enrollment->payments->pluck('status')->unique()->implode(', ');
            $paymentStatuses = $paymentStatuses ?: 'Aucun paiement';
            
            $tableData[] = [
                'ID' => $enrollment->id,
                'Utilisateur' => $enrollment->user->first_name . ' ' . $enrollment->user->last_name,
                'Cours' => $enrollment->course->title,
                'Prix' => $enrollment->course->price . ' ' . $enrollment->course->currency,
                'Activé le' => $enrollment->enrolled_at->format('Y-m-d H:i:s'),
                'Statuts paiements' => $paymentStatuses,
            ];
        }

        $this->table([
            'ID', 'Utilisateur', 'Cours', 'Prix', 'Activé le', 'Statuts paiements'
        ], $tableData);

        if ($isDryRun) {
            $this->info('🔍 Mode DRY-RUN : Aucune modification effectuée');
            return 0;
        }

        if (!$this->confirm('Voulez-vous corriger ces inscriptions invalides ?')) {
            $this->info('❌ Opération annulée');
            return 0;
        }

        $this->info('🔧 Correction des inscriptions invalides...');

        $corrected = 0;
        foreach ($invalidEnrollments as $enrollment) {
            try {
                // Désactiver l'inscription
                $enrollment->update(['enrolled_at' => null]);
                
                Log::info('Inscription invalide corrigée par commande', [
                    'enrollment_id' => $enrollment->id,
                    'user_id' => $enrollment->user_id,
                    'course_id' => $enrollment->course_id,
                    'previous_enrolled_at' => $enrollment->enrolled_at
                ]);
                
                $corrected++;
                
                $this->line("✅ Inscription #{$enrollment->id} corrigée");
                
            } catch (\Exception $e) {
                $this->error("❌ Erreur lors de la correction de l'inscription #{$enrollment->id}: " . $e->getMessage());
                Log::error('Erreur correction inscription invalide', [
                    'enrollment_id' => $enrollment->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->newLine();
        $this->info("🎉 Correction terminée ! {$corrected} inscription(s) corrigée(s) sur {$invalidEnrollments->count()} trouvée(s)");
        
        return 0;
    }
}