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
        Schema::create('formateur_community_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Type d'événement
            $table->enum('event_type', ['meeting', 'webinar', 'workshop', 'announcement'])->default('meeting');
            
            // Informations
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('agenda')->nullable();
            
            // Dates
            $table->timestamp('scheduled_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            
            // Lien/URL
            $table->string('meeting_url')->nullable();
            $table->string('recording_url')->nullable();
            
            // Participants
            $table->integer('attendance_count')->default(0);
            $table->json('participants_ids')->nullable();
            
            // Statut
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            
            $table->timestamps();
            
            $table->index('event_type');
            $table->index('scheduled_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_community_events');
    }
};

