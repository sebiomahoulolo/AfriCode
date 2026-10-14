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
        // Ne rien faire si les tables n'existent pas encore (ex: tests avec RefreshDatabase, ordre des migrations)
        if (!Schema::hasTable('lesson_completions')) {
            return;
        }

        // Corriger les enregistrements de lesson_completions qui ont completed_at null
        // mais qui devraient être marqués comme complétés
        DB::statement("
            UPDATE lesson_completions 
            SET completed_at = created_at 
            WHERE completed_at IS NULL 
            AND created_at IS NOT NULL
        ");

        // Marquer toutes les leçons existantes comme pouvant être complétées (si la colonne existe)
        if (Schema::hasColumn('lesson_completions', 'can_complete')) {
            DB::statement("
                UPDATE lesson_completions 
                SET can_complete = true 
                WHERE can_complete = false
            ");
        }

        // Mettre à jour les progress_percentage des enrollments
        if (Schema::hasTable('enrollments')) {
            DB::statement("
                UPDATE enrollments e
                SET progress_percentage = (
                    SELECT COALESCE(
                        ROUND(
                            (COUNT(lc.id) * 100.0) / NULLIF(
                                (SELECT COUNT(l.id) 
                                 FROM lessons l 
                                 JOIN modules m ON l.module_id = m.id 
                                 WHERE m.course_id = e.course_id), 0
                            )
                        ), 0
                    )
                    FROM lesson_completions lc
                    JOIN lessons l ON lc.lesson_id = l.id
                    JOIN modules m ON l.module_id = m.id
                    WHERE lc.user_id = e.user_id 
                    AND m.course_id = e.course_id
                    AND lc.completed_at IS NOT NULL
                )
                WHERE progress_percentage IS NULL OR progress_percentage = 0
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration ne peut pas être inversée car elle corrige des données
        // Il n'y a pas de rollback nécessaire
    }
};
