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
        if (!Schema::hasTable('tutoring_session_resources')) {
            Schema::create('tutoring_session_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tutoring_sessions')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['link', 'file', 'document', 'video', 'image'])->default('link');
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoring_session_resources');
    }
};