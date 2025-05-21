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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('content_type', ['video', 'text', 'pdf', 'quiz_link', 'external_link']);
            $table->string('video_url')->nullable();
            $table->text('text_content')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('external_url')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_previewable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
