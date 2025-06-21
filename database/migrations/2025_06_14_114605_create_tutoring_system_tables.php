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
        // Table pour les tuteurs
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->json('expertise_areas')->nullable();
            $table->json('languages')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->json('availability_schedule')->nullable();
            $table->integer('rating')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Table pour les sessions de tutorat
        Schema::create('tutoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->enum('type', ['one_on_one', 'group', 'workshop'])->default('one_on_one');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration_minutes');
            $table->decimal('price', 10, 2);
            $table->string('meeting_link')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Table pour les participants des sessions de groupe
        Schema::create('tutoring_session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tutoring_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['student', 'observer'])->default('student');
            $table->boolean('has_joined')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
        });

        // Table pour les ressources partagées pendant les sessions
        Schema::create('tutoring_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tutoring_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // document, link, code, etc.
            $table->string('file_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Table pour les feedbacks des sessions
        Schema::create('tutoring_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tutoring_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->json('specific_ratings')->nullable(); // Pour des évaluations détaillées (clarté, patience, etc.)
            $table->timestamps();
        });

        // Table pour les objectifs d'apprentissage
        Schema::create('tutoring_learning_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tutoring_sessions')->onDelete('cascade');
            $table->string('objective');
            $table->boolean('is_achieved')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoring_learning_objectives');
        Schema::dropIfExists('tutoring_feedback');
        Schema::dropIfExists('tutoring_resources');
        Schema::dropIfExists('tutoring_session_participants');
        Schema::dropIfExists('tutoring_sessions');
        Schema::dropIfExists('tutors');
    }
};
