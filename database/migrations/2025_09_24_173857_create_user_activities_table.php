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
        if (!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type'); // Type d'activité (course_completed, lesson_completed, quiz_passed, etc.)
                $table->text('description'); // Description de l'activité
                $table->json('metadata')->nullable(); // Métadonnées supplémentaires (course_id, lesson_id, etc.)
                $table->integer('points_earned')->default(0); // Points gagnés pour cette activité
                $table->timestamps();
                
                $table->index(['user_id', 'type']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
