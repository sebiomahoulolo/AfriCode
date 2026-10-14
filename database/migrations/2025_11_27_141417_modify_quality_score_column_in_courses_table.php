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
        // MODIFY COLUMN est spécifique à MySQL ; on ignore pour SQLite (tests)
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        // Modifier la colonne quality_score pour permettre des valeurs de 0 à 100
        DB::statement('ALTER TABLE `courses` MODIFY COLUMN `quality_score` DECIMAL(5, 2) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement('ALTER TABLE `courses` MODIFY COLUMN `quality_score` DECIMAL(3, 2) NULL');
    }
};
