<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixInvalidEnrollment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:fix-invalid {enrollment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige une inscription invalide spécifique';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enrollmentId = $this->argument('enrollment_id');
        
        $enrollment = Enrollment::with(['course', 'user', 'payments'])
            ->find($enrollmentId);

        if (!$enrollment) {
            $this->error("❌ Inscription #{$enrollmentId} non trouvée");
            return 1;
        }

        $this->info("🔍 Inscription trouvée :");
        $this->line("   - Utilisateur: {$enrollment->user->first_name} {$enrollment->user->last_name}");
        $this->line("   - Cours: {$enrollment->course->title}");
        $this->line("   - Prix: {$enrollment->course->price} {$enrollment->course->currency}");
        $this->line("   - Activé le: " . ($enrollment->enrolled_at ? $enrollment->enrolled_at->format('Y-m-d H:i:s') : 'Non activé'));
        
        $paymentStatuses = $enrollment->payments->pluck('status')->unique()->implode(', ');
        $paymentStatuses = $paymentStatuses ?: 'Aucun paiement';
        $this->line("   - Statuts paiements: {$paymentStatuses}");

        // Vérifier si c'est vraiment invalide
        if ($enrollment->course->price > 0) {
            $successfulPayment = $enrollment->payments()
                ->where('status', 'succeeded')
                ->first();
                
            if (!$successfulPayment && $enrollment->enrolled_at) {
                $this->warn("⚠️  Inscription invalide détectée (activée sans paiement réussi)");
                
                try {
                    // Désactiver l'inscription
                    $enrollment->update(['enrolled_at' => null]);
                    
                    Log::info('Inscription invalide corrigée par commande fix-invalid', [
                        'enrollment_id' => $enrollment->id,
                        'user_id' => $enrollment->user_id,
                        'course_id' => $enrollment->course_id,
                        'previous_enrolled_at' => $enrollment->enrolled_at
                    ]);
                    
                    $this->info("✅ Inscription #{$enrollmentId} corrigée avec succès !");
                    $this->info("🎉 L'utilisateur peut maintenant refaire un achat");
                    
                } catch (\Exception $e) {
                    $this->error("❌ Erreur lors de la correction: " . $e->getMessage());
                    return 1;
                }
            } else {
                $this->info("✅ Inscription valide, aucune correction nécessaire");
            }
        } else {
            $this->info("ℹ️  Cours gratuit, aucune vérification de paiement nécessaire");
        }

        return 0;
    }
}
