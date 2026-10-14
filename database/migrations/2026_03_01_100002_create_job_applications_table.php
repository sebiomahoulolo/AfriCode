<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_applications')) {
            return;
        }
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_position_id')->constrained()->cascadeOnDelete();
            $table->string('nom_prenoms');
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('adresse')->nullable();
            $table->string('offer_source')->nullable(); // Facebook, Instagram, etc.
            $table->string('offer_source_other')->nullable();
            $table->json('form_data')->nullable(); // Champs spécifiques au poste (github, portfolio, etc.)
            $table->unsignedTinyInteger('test_score')->nullable(); // 0-10
            $table->json('test_answers')->nullable(); // { "1": "B", "2": "C", ... }
            $table->string('cv_path')->nullable();
            $table->string('lettre_path')->nullable();
            $table->json('other_documents')->nullable(); // [{ "name": "...", "path": "..." }]
            $table->string('status')->default('draft'); // draft, form_submitted, test_completed
            $table->string('token')->unique()->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
