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
        if (Schema::hasColumn('courses', 'validation_status')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            // Statut de validation (remplace pending_approval par pending_validation)
            $table->enum('validation_status', ['draft', 'pending_validation', 'needs_revision', 'approved', 'rejected'])->default('draft')->after('status');
            
            // Notes de validation
            $table->text('admin_notes')->nullable()->after('validation_status');
            $table->text('revision_notes')->nullable()->after('admin_notes');
            
            // Validation
            $table->foreignId('validated_by')->nullable()->after('revision_notes')->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable()->after('validated_by');
            $table->timestamp('submitted_for_validation_at')->nullable()->after('validated_at');
            
            // Qualité
            $table->decimal('quality_score', 3, 2)->nullable()->after('validated_at'); // Note de qualité 0-5
            $table->integer('completion_rate')->nullable()->after('quality_score'); // Taux de complétion %
            $table->integer('average_study_time')->nullable()->after('completion_rate'); // Temps moyen en minutes
            
            // Détection de plagiat
            $table->boolean('plagiarism_checked')->default(false)->after('average_study_time');
            $table->decimal('plagiarism_score', 5, 2)->nullable()->after('plagiarism_checked'); // Score de plagiat %
            $table->timestamp('last_updated_at')->nullable()->after('plagiarism_score');
            
            // Mise à jour obligatoire
            $table->boolean('update_required')->default(false)->after('last_updated_at');
            $table->text('update_reason')->nullable()->after('update_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn([
                'validation_status',
                'admin_notes',
                'revision_notes',
                'validated_by',
                'validated_at',
                'submitted_for_validation_at',
                'quality_score',
                'completion_rate',
                'average_study_time',
                'plagiarism_checked',
                'plagiarism_score',
                'last_updated_at',
                'update_required',
                'update_reason',
            ]);
        });
    }
};
