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
            // Vérifier si les colonnes existent avant de les supprimer
            if (Schema::hasColumn('payments', 'payable_id')) {
                $table->dropColumn('payable_id');
            }
            if (Schema::hasColumn('payments', 'payable_type')) {
                $table->dropColumn('payable_type');
            }
            
            // Ajouter course_id seulement si elle n'existe pas déjà
            if (!Schema::hasColumn('payments', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('user_id');
            }
        });

        // Nettoyer les données invalides avant d'ajouter la contrainte
        try {
            DB::statement('DELETE FROM payments WHERE course_id IS NULL OR course_id NOT IN (SELECT id FROM courses)');
            
            // Ajouter la contrainte de clé étrangère dans une deuxième étape
            Schema::table('payments', function (Blueprint $table) {
                if (!$this->foreignKeyExists('payments', 'payments_course_id_foreign')) {
                    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                }
            });
        } catch (\Exception $e) {
            // Ignorer les erreurs si la contrainte ne peut pas être ajoutée
        }
    }

    /**
     * Vérifier si une contrainte de clé étrangère existe
     */
    private function foreignKeyExists($table, $name)
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ?
        ", [$table, $name]);
        
        return count($result) > 0;
    }
};
