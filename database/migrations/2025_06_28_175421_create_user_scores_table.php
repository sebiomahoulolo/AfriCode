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
        Schema::create('user_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('leaderboard_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0); // Score total
            $table->integer('rank')->nullable(); // Position dans le classement
            $table->json('score_breakdown')->nullable(); // Détail des points (ex: défis, badges, etc.)
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();
            
            // Un utilisateur ne peut avoir qu'un score par classement
            $table->unique(['user_id', 'leaderboard_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_scores');
    }
};
