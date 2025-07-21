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
        // Suppression sécurisée de la contrainte si elle existe
        try {
            DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_course_id_foreign');
        } catch (\Exception $e) {
            // La contrainte n’existe pas ou déjà supprimée : on ignore
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('course_id');

            // Ajout des colonnes pour relation polymorphique
            $table->unsignedBigInteger('payable_id')->nullable()->after('user_id')->index();
            $table->string('payable_type')->nullable()->after('payable_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Rétablir course_id avec contrainte étrangère
            $table->foreignId('course_id')->constrained()->onDelete('cascade');

            // Supprimer les colonnes polymorphiques
            $table->dropColumn(['payable_id', 'payable_type']);
        });
    }
};
