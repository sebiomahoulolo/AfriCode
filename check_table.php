<?php

require_once 'vendor/autoload.php';

// Charger l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Vérification de la table quiz_attempts ===\n\n";

try {
    // Vérifier si la table existe
    if (Schema::hasTable('quiz_attempts')) {
        echo "✅ La table quiz_attempts existe\n";
        
        // Récupérer les colonnes
        $columns = Schema::getColumnListing('quiz_attempts');
        echo "Colonnes disponibles :\n";
        foreach ($columns as $column) {
            echo "- $column\n";
        }
        
        // Vérifier spécifiquement la colonne status
        if (Schema::hasColumn('quiz_attempts', 'status')) {
            echo "\n✅ La colonne 'status' existe\n";
        } else {
            echo "\n❌ La colonne 'status' n'existe PAS\n";
        }
        
        // Compter les enregistrements
        $count = DB::table('quiz_attempts')->count();
        echo "\nNombre d'enregistrements : $count\n";
        
    } else {
        echo "❌ La table quiz_attempts n'existe pas\n";
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== Fin de la vérification ===\n";

