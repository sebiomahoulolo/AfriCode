<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter les contraintes de clés étrangères pour la table courses
        Schema::table('courses', function (Blueprint $table) {
        $table->foreign('formateur_id', 'courses_formateur_id_fk')
      ->references('id')->on('users')
      ->onDelete('cascade');

                  
            $table->foreign('category_id', 'courses_category_id_fk')
      ->references('id')->on('categories')
      ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['formateur_id']);
            $table->dropForeign(['category_id']);
        });
    }
};
