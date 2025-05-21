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
        Schema::create('competition_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('competition_registrations')->onDelete('cascade')->unique();
            $table->string('project_title');
            $table->text('project_description')->nullable();
            $table->string('project_link_repository')->nullable();
            $table->string('project_link_live')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->decimal('score', 5, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->text('feedback_from_judges')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_submissions');
    }
};
