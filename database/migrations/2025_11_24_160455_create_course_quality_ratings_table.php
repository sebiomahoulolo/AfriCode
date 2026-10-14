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
        if (Schema::hasTable('course_quality_ratings')) {
            return;
        }

        Schema::create('course_quality_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('rated_by')->nullable()->constrained('users')->onDelete('set null'); // Admin ou système
            
            // Note globale
            $table->decimal('overall_rating', 3, 2); // 0-5
            
            // Notes détaillées
            $table->decimal('clarity_rating', 3, 2)->nullable(); // Clarté
            $table->decimal('structure_rating', 3, 2)->nullable(); // Structure
            $table->decimal('quality_rating', 3, 2)->nullable(); // Qualité technique
            $table->decimal('pedagogy_rating', 3, 2)->nullable(); // Pédagogie
            $table->decimal('content_rating', 3, 2)->nullable(); // Contenu
            
            // Métriques
            $table->integer('completion_rate')->nullable(); // Taux de complétion %
            $table->integer('average_study_time')->nullable(); // Temps moyen en minutes
            $table->integer('student_satisfaction')->nullable(); // Satisfaction % (basée sur avis)
            $table->integer('quiz_pass_rate')->nullable(); // Taux de réussite aux quiz %
            
            // Commentaires
            $table->text('strengths')->nullable(); // Points forts
            $table->text('weaknesses')->nullable(); // Points faibles
            $table->text('recommendations')->nullable(); // Recommandations
            
            // Action requise
            $table->enum('action_required', ['none', 'monitoring', 'improvement', 'urgent'])->default('none');
            $table->text('action_notes')->nullable();
            
            $table->timestamp('rated_at');
            $table->timestamps();
            
            $table->index('course_id');
            $table->index('overall_rating');
            $table->index('action_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_quality_ratings');
    }
};
