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
        if (Schema::hasTable('course_validations')) {
            return;
        }

        Schema::create('course_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('validator_id')->constrained('users')->onDelete('cascade'); // Admin qui valide
            $table->enum('status', ['pending', 'approved', 'needs_revision', 'rejected'])->default('pending');
            
            // Critères de validation
            $table->boolean('clarity_check')->default(false);
            $table->boolean('structure_check')->default(false);
            $table->boolean('quality_check')->default(false);
            $table->boolean('technical_check')->default(false);
            $table->boolean('plagiarism_check')->default(false);
            $table->boolean('pedagogy_check')->default(false);
            
            // Notes
            $table->text('pedagogical_notes')->nullable();
            $table->text('technical_notes')->nullable();
            $table->text('general_notes')->nullable();
            $table->text('revision_notes')->nullable();
            
            // Scores
            $table->decimal('pedagogical_score', 3, 2)->nullable(); // 0-5
            $table->decimal('technical_score', 3, 2)->nullable(); // 0-5
            $table->decimal('overall_score', 3, 2)->nullable(); // 0-5
            
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->index('course_id');
            $table->index('validator_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_validations');
    }
};
