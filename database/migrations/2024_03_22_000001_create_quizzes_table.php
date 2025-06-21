<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('time_limit')->nullable(); // en minutes
            $table->integer('passing_score')->nullable();
            $table->boolean('is_adaptive')->default(false);
            $table->boolean('is_realtime')->default(false);
            $table->boolean('is_collaborative')->default(false);
            $table->boolean('allow_media')->default(false);
            $table->boolean('anti_cheat_enabled')->default(false);
            $table->json('settings')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}; 