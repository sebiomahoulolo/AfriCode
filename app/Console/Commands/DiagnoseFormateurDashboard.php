<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class DiagnoseFormateurDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'formateur:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostique les problèmes du dashboard formateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DIAGNOSTIC DASHBOARD FORMATEUR ===');
        $this->newLine();

        try {
            // 1. Vérifier la connexion à la base de données
            $this->info('1. Test de connexion à la base de données...');
            DB::connection()->getPdo();
            $this->info('✓ Connexion à la base de données réussie');
            $this->newLine();
            
            // 2. Vérifier les utilisateurs formateurs
            $this->info('2. Vérification des utilisateurs formateurs...');
            $formateurs = User::where('role', 'formateur')->get();
            $this->info("Nombre de formateurs trouvés: " . $formateurs->count());
            
            if ($formateurs->count() > 0) {
                $formateur = $formateurs->first();
                $this->info("Premier formateur: ID " . $formateur->id . " - " . $formateur->first_name . " " . $formateur->last_name);
                
                // 3. Vérifier les cours de ce formateur
                $this->newLine();
                $this->info('3. Vérification des cours du formateur...');
                $courses = Course::where('formateur_id', $formateur->id)->get();
                $this->info("Nombre de cours trouvés: " . $courses->count());
                
                if ($courses->count() > 0) {
                    foreach ($courses as $course) {
                        $this->line("- Cours: " . $course->title . " (ID: " . $course->id . ", Statut: " . $course->status . ")");
                    }
                    
                    // 4. Vérifier les inscriptions
                    $this->newLine();
                    $this->info('4. Vérification des inscriptions...');
                    $enrollments = Enrollment::whereIn('course_id', $courses->pluck('id'))->get();
                    $this->info("Nombre d'inscriptions: " . $enrollments->count());
                    
                    // 5. Vérifier les paiements
                    $this->newLine();
                    $this->info('5. Vérification des paiements...');
                    $payments = Payment::where('payable_type', Course::class)
                        ->whereIn('payable_id', $courses->pluck('id'))
                        ->where('status', 'succeeded')
                        ->get();
                    $this->info("Nombre de paiements réussis: " . $payments->count());
                    $this->info("Montant total: " . $payments->sum('amount') . "€");
                    
                    // 6. Vérifier les notes
                    $this->newLine();
                    $this->info('6. Vérification des notes...');
                    $ratings = Rating::whereIn('course_id', $courses->pluck('id'))->get();
                    $this->info("Nombre de notes: " . $ratings->count());
                    if ($ratings->count() > 0) {
                        $this->info("Note moyenne: " . $ratings->avg('rating'));
                    }
                } else {
                    $this->warn('⚠️  Aucun cours trouvé pour ce formateur');
                }
            } else {
                $this->warn('⚠️  Aucun formateur trouvé dans la base de données');
            }
            
            // 7. Vérifier la structure des tables
            $this->newLine();
            $this->info('7. Vérification de la structure des tables...');
            $tables = ['users', 'courses', 'enrollments', 'payments', 'ratings'];
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("Table $table: $count enregistrements");
                } catch (\Exception $e) {
                    $this->error("⚠️  Erreur avec la table $table: " . $e->getMessage());
                }
            }
            
            $this->newLine();
            $this->info('=== DIAGNOSTIC TERMINÉ ===');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du diagnostic: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
