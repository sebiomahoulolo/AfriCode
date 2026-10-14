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
        if (Schema::hasTable('formateur_badges')) {
            return;
        }

        Schema::create('formateur_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
            $table->enum('badge_type', ['bronze', 'argent', 'or', 'platine'])->default('bronze');
            $table->string('badge_name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Chemin vers l'icône
            $table->json('criteria')->nullable(); // Critères d'obtention
            $table->timestamp('earned_at');
            $table->timestamps();
            
            $table->index('formateur_id');
            $table->index('badge_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_badges');
    }
};
