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
        Schema::create('formateur_disciplinary_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Type d'action
            $table->enum('action_type', ['warning', 'suspension_7d', 'suspension_30d', 'ban'])->default('warning');
            
            // Raison
            $table->text('reason');
            $table->text('details')->nullable();
            
            // Référence (cours, etc.)
            $table->string('reference_type')->nullable(); // 'course', 'general', etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            
            // Dates
            $table->timestamp('action_date');
            $table->timestamp('expires_at')->nullable(); // Pour les suspensions
            $table->boolean('is_active')->default(true);
            
            // Résolution
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            
            $table->index('formateur_id');
            $table->index('action_type');
            $table->index('is_active');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_disciplinary_actions');
    }
};

