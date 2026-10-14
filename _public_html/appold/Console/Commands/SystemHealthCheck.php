<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class SystemHealthCheck extends Command
{
    protected $signature = 'system:health-check {--fix : Corriger automatiquement les problèmes détectés}';
    protected $description = 'Vérifie la santé du système et détecte les problèmes potentiels';

    public function handle()
    {
        $this->info('🔍 Vérification de la santé du système AfriCode...');
        
        $issues = [];
        $fixes = [];

        // 1. Vérification de la base de données
        $this->checkDatabase($issues, $fixes);
        
        // 2. Vérification des fichiers de stockage
        $this->checkStorage($issues, $fixes);
        
        // 3. Vérification des données orphelines
        $this->checkOrphanedData($issues, $fixes);
        
        // 4. Vérification des performances
        $this->checkPerformance($issues, $fixes);
        
        // 5. Vérification de la sécurité
        $this->checkSecurity($issues, $fixes);

        // Affichage des résultats
        $this->displayResults($issues, $fixes);
        
        // Application des corrections si demandé
        if ($this->option('fix') && !empty($fixes)) {
            $this->applyFixes($fixes);
        }

        return empty($issues) ? 0 : 1;
    }

    private function checkDatabase(&$issues, &$fixes)
    {
        $this->info('📊 Vérification de la base de données...');
        
        try {
            DB::connection()->getPdo();
            $this->line('✅ Connexion à la base de données OK');
        } catch (\Exception $e) {
            $issues[] = "❌ Problème de connexion à la base de données: " . $e->getMessage();
            return;
        }

        // Vérifier les tables manquantes
        $requiredTables = ['users', 'courses', 'enrollments', 'lessons', 'modules'];
        foreach ($requiredTables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $issues[] = "❌ Table manquante: {$table}";
            }
        }
    }

    private function checkStorage(&$issues, &$fixes)
    {
        $this->info('💾 Vérification du stockage...');
        
        // Vérifier l'espace disque
        $freeSpace = disk_free_space(storage_path());
        $totalSpace = disk_total_space(storage_path());
        $usedPercentage = (($totalSpace - $freeSpace) / $totalSpace) * 100;
        
        if ($usedPercentage > 90) {
            $issues[] = "⚠️ Espace disque critique: {$usedPercentage}% utilisé";
        } elseif ($usedPercentage > 80) {
            $issues[] = "⚠️ Espace disque faible: {$usedPercentage}% utilisé";
        } else {
            $this->line('✅ Espace disque OK');
        }

        // Vérifier les permissions
        if (!is_writable(storage_path())) {
            $issues[] = "❌ Permissions d'écriture manquantes sur storage/";
        }
    }

    private function checkOrphanedData(&$issues, &$fixes)
    {
        $this->info('🔍 Vérification des données orphelines...');
        
        // Enrollments sans cours
        $orphanedEnrollments = Enrollment::whereDoesntHave('course')->count();
        if ($orphanedEnrollments > 0) {
            $issues[] = "❌ {$orphanedEnrollments} inscriptions orphelines (sans cours)";
            $fixes[] = function() use ($orphanedEnrollments) {
                Enrollment::whereDoesntHave('course')->delete();
                $this->line("✅ Supprimé {$orphanedEnrollments} inscriptions orphelines");
            };
        }

        // Utilisateurs sans email
        $usersWithoutEmail = User::whereNull('email')->orWhere('email', '')->count();
        if ($usersWithoutEmail > 0) {
            $issues[] = "❌ {$usersWithoutEmail} utilisateurs sans email";
        }
    }

    private function checkPerformance(&$issues, &$fixes)
    {
        $this->info('⚡ Vérification des performances...');
        
        // Vérifier le cache
        try {
            Cache::put('health_check', 'test', 60);
            if (Cache::get('health_check') !== 'test') {
                $issues[] = "❌ Problème avec le système de cache";
            } else {
                $this->line('✅ Cache OK');
            }
            Cache::forget('health_check');
        } catch (\Exception $e) {
            $issues[] = "❌ Erreur de cache: " . $e->getMessage();
        }

        // Vérifier les requêtes lentes potentielles
        $largeTables = [
            'users' => User::count(),
            'courses' => Course::count(),
            'enrollments' => Enrollment::count()
        ];

        foreach ($largeTables as $table => $count) {
            if ($count > 10000) {
                $issues[] = "⚠️ Table {$table} très volumineuse ({$count} enregistrements) - considérer l'indexation";
            }
        }
    }

    private function checkSecurity(&$issues, &$fixes)
    {
        $this->info('🔒 Vérification de la sécurité...');
        
        // Vérifier les utilisateurs avec des mots de passe faibles
        $weakPasswords = User::where('password', 'like', 'password%')
            ->orWhere('password', 'like', '123456%')
            ->count();
            
        if ($weakPasswords > 0) {
            $issues[] = "⚠️ {$weakPasswords} utilisateurs avec des mots de passe potentiellement faibles";
        }

        // Vérifier les cours sans formateur
        $coursesWithoutInstructor = Course::whereNull('formateur_id')->count();
        if ($coursesWithoutInstructor > 0) {
            $issues[] = "❌ {$coursesWithoutInstructor} cours sans formateur assigné";
        }
    }

    private function displayResults($issues, $fixes)
    {
        $this->newLine();
        
        if (empty($issues)) {
            $this->info('🎉 Aucun problème détecté ! Le système est en bonne santé.');
        } else {
            $this->error('🚨 Problèmes détectés:');
            foreach ($issues as $issue) {
                $this->line($issue);
            }
            
            if (!empty($fixes)) {
                $this->newLine();
                $this->info('🔧 Corrections disponibles:');
                foreach ($fixes as $index => $fix) {
                    $this->line("  " . ($index + 1) . ". Correction automatique disponible");
                }
                
                $this->newLine();
                $this->comment('💡 Utilisez --fix pour appliquer les corrections automatiques');
            }
        }
    }

    private function applyFixes($fixes)
    {
        $this->newLine();
        $this->info('🔧 Application des corrections...');
        
        foreach ($fixes as $index => $fix) {
            try {
                $fix();
            } catch (\Exception $e) {
                $this->error("❌ Erreur lors de l'application de la correction " . ($index + 1) . ": " . $e->getMessage());
            }
        }
        
        $this->info('✅ Corrections appliquées');
    }
}
