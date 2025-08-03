<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la table existe avant de la modifier
        if (!Schema::hasTable('quizzes')) {
            return;
        }

        Schema::table('quizzes', function (Blueprint $table) {
            // ✅ Ajout sécurisé de module_id
            if (!Schema::hasColumn('quizzes', 'module_id')) {
                $table->foreignId('module_id')->nullable()->after('description')
                      ->constrained('modules')->onDelete('cascade');
            }

            // ✅ Ajout sécurisé de course_id
            if (!Schema::hasColumn('quizzes', 'course_id')) {
                $table->foreignId('course_id')->nullable()->after('module_id')
                      ->constrained('courses')->onDelete('cascade');
            }

            // ✅ Ajout sécurisé de quiz_type
            if (!Schema::hasColumn('quizzes', 'quiz_type')) {
                $table->enum('quiz_type', ['module_end', 'course_final'])
                      ->default('module_end')->after('course_id');
            }

            // ✅ Ajout sécurisé de order
            if (!Schema::hasColumn('quizzes', 'order')) {
                $table->integer('order')->default(0)->after('time_limit_minutes');
            }

            // 🔥 Suppression des colonnes polymorphiques si elles existent
            if (Schema::hasColumn('quizzes', 'related_id')) {
                $table->dropColumn('related_id');
            }
            if (Schema::hasColumn('quizzes', 'related_type')) {
                $table->dropColumn('related_type');
            }

            // ✅ Ajout sécurisé de is_required
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
        // Vérifier si la table existe avant de la modifier
        if (!Schema::hasTable('quizzes')) {
            return;
        }

        // Supprimer les contraintes de clé étrangère de manière sécurisée avec SQL direct
        try {
            DB::statement('ALTER TABLE quizzes DROP FOREIGN KEY quizzes_module_id_foreign');
        } catch (\Exception $e) {
            // La contrainte n'existe pas, on continue
        }

        try {
            DB::statement('ALTER TABLE quizzes DROP FOREIGN KEY quizzes_course_id_foreign');
        } catch (\Exception $e) {
            // La contrainte n'existe pas, on continue
        }

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'module_id')) {
                $table->dropColumn('module_id');
            }

            if (Schema::hasColumn('quizzes', 'course_id')) {
                $table->dropColumn('course_id');
            }

            if (Schema::hasColumn('quizzes', 'quiz_type')) {
                $table->dropColumn('quiz_type');
            }

            if (Schema::hasColumn('quizzes', 'order')) {
                $table->dropColumn('order');
            }

            if (Schema::hasColumn('quizzes', 'is_required')) {
                $table->dropColumn('is_required');
            }

            // 🔄 Rétablir les colonnes polymorphiques
            if (!Schema::hasColumn('quizzes', 'related_id')) {
                $table->unsignedBigInteger('related_id');
            }
            if (!Schema::hasColumn('quizzes', 'related_type')) {
                $table->string('related_type');
            }
        });
    }
};
