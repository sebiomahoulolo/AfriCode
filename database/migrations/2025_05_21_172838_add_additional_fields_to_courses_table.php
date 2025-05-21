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
        Schema::table('courses', function (Blueprint $table) {
            // Ajouter les champs pour les objectifs d'apprentissage (ce que vous apprendrez)
            $table->json('learning_objectives')->nullable()->after('full_description');
            
            // Ajouter les champs pour les prérequis
            $table->json('prerequisites')->nullable()->after('learning_objectives');
            
            // Ajouter les champs pour les questions fréquemment posées
            $table->json('faq')->nullable()->after('prerequisites');
            
            // Ajouter les champs pour les témoignages spécifiques à ce cours
            $table->json('testimonials')->nullable()->after('faq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'learning_objectives',
                'prerequisites',
                'faq',
                'testimonials'
            ]);
        });
    }
};
