<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'enum content_type pour accepter 'external' au lieu de 'external_link'
        DB::statement("ALTER TABLE lessons MODIFY COLUMN content_type ENUM('video', 'text', 'pdf', 'external') NOT NULL");
        
        // Mettre à jour les valeurs existantes si nécessaire
        DB::table('lessons')
            ->where('content_type', 'external_link')
            ->update(['content_type' => 'external']);
            
        DB::table('lessons')
            ->where('content_type', 'quiz_link')
            ->update(['content_type' => 'external']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer l'ancien enum
        DB::statement("ALTER TABLE lessons MODIFY COLUMN content_type ENUM('video', 'text', 'pdf', 'quiz_link', 'external_link') NOT NULL");
        
        // Restaurer les valeurs
        DB::table('lessons')
            ->where('content_type', 'external')
            ->update(['content_type' => 'external_link']);
    }
};
