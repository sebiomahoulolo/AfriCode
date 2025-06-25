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
        Schema::table('quizzes', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes
            $table->foreignId('module_id')->nullable()->after('description')->constrained('modules')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->after('module_id')->constrained('courses')->onDelete('cascade');
            $table->enum('quiz_type', ['module_end', 'course_final'])->after('course_id')->default('module_end');
            $table->integer('order')->after('time_limit_minutes')->default(0);
            
            // Supprimer les anciennes colonnes polymorphiques si elles existent
            if (Schema::hasColumn('quizzes', 'related_id')) {
                $table->dropColumn(['related_id', 'related_type']);
            }

            // Ajouter la colonne is_required si elle n'existe pas déjà
            if (!Schema::hasColumn('quizzes', 'is_required')) {
                $table->boolean('is_required')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'module_id')) {
                $table->dropForeign(['module_id']);
                $table->dropForeign(['course_id']);
                $table->dropColumn(['module_id', 'course_id', 'quiz_type', 'order']);
            }
            
            // Rétablir les anciennes colonnes
            $table->unsignedBigInteger('related_id');
            $table->string('related_type');
        });
    }
};
