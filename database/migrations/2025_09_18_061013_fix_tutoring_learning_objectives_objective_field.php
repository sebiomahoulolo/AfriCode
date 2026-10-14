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
        // Modifier le champ objective pour qu'il soit nullable ou ait une valeur par défaut
        Schema::table('tutoring_learning_objectives', function (Blueprint $table) {
            // Rendre le champ nullable pour éviter l'erreur MySQL
            $table->string('objective')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutoring_learning_objectives', function (Blueprint $table) {
            // Remettre le champ comme non-nullable
            $table->string('objective')->nullable(false)->change();
        });
    }
};