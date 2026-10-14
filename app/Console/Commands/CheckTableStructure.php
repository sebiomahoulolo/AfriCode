<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'table:check-structure {table}';
    protected $description = 'Vérifie la structure d\'une table';

    public function handle()
    {
        $tableName = $this->argument('table');
        
        $this->info("🔍 Structure de la table: {$tableName}");
        
        try {
            $columns = Schema::getColumnListing($tableName);
            
            $this->info("📊 Colonnes trouvées:");
            foreach ($columns as $column) {
                $this->line("  - {$column}");
            }
            
            // Vérifier quelques enregistrements
            $count = DB::table($tableName)->count();
            $this->info("📝 Nombre d'enregistrements: {$count}");
            
            if ($count > 0) {
                $sample = DB::table($tableName)->first();
                $this->info("📋 Exemple d'enregistrement:");
                foreach ((array)$sample as $key => $value) {
                    $this->line("  {$key}: {$value}");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur: " . $e->getMessage());
        }
        
        return 0;
    }
}
