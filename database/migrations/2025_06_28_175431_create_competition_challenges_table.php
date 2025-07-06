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
        Schema::create('competition_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->integer('points_reward')->default(0); // Points gagnés pour ce défi dans cette compétition
            $table->boolean('is_required')->default(false); // Si le défi est obligatoire pour la compétition
            $table->integer('order')->default(0); // Ordre d'affichage dans la compétition
            $table->timestamps();
            
            // Un défi ne peut être associé qu'une fois à une compétition
            $table->unique(['competition_id', 'challenge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_challenges');
    }
};
