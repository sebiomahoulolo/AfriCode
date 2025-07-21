<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table pour les médias de quiz
        Schema::create('quiz_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('type'); // image, video, audio
            $table->string('url');
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Table pour les tentatives de quiz
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->integer('score')->nullable();
            $table->string('status')->default('completed');
            $table->json('answers')->nullable();
            $table->json('anti_cheat_data')->nullable();
            $table->timestamps();
        });

        // Table pour les sessions de quiz collaboratif
        Schema::create('collaborative_quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('session_code')->unique();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->json('participants')->nullable();
            $table->json('group_answers')->nullable();
            $table->timestamps();
        });

        // Table pour les règles anti-triche
        Schema::create('anti_cheat_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // tab_switch, copy_paste, time_limit, etc.
            $table->json('parameters');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table pour les violations anti-triche
        Schema::create('anti_cheat_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('anti_cheat_rule_id')->constrained()->onDelete('cascade');
            $table->string('violation_type');
            $table->json('violation_data');
            $table->timestamp('detected_at');
            $table->boolean('is_verified')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // Table pour les questions adaptatives
        Schema::create('adaptive_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('difficulty_level'); // easy, medium, hard
            $table->integer('points');
            $table->json('conditions'); // Conditions pour afficher cette question
            $table->json('next_questions'); // Questions suivantes possibles
            $table->timestamps();
        });

        // Table pour les sessions de quiz en temps réel
        Schema::create('realtime_quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('session_code')->unique();
            $table->timestamp('scheduled_start');
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('participants')->nullable();
            $table->json('leaderboard')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('realtime_quiz_sessions');
        Schema::dropIfExists('adaptive_questions');
        Schema::dropIfExists('anti_cheat_violations');
        Schema::dropIfExists('anti_cheat_rules');
        Schema::dropIfExists('collaborative_quiz_sessions');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_media');
    }
}; 