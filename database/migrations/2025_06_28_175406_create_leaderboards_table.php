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
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du classement (ex: "Global", "Hebdomadaire", "Mensuel")
            $table->string('type'); // global, weekly, monthly, competition_specific
            $table->foreignId('competition_id')->nullable()->constrained()->onDelete('cascade'); // Si spécifique à une compétition
            $table->timestamp('start_date')->nullable(); // Date de début du classement
            $table->timestamp('end_date')->nullable(); // Date de fin du classement
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Paramètres supplémentaires (ex: règles de calcul)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};
