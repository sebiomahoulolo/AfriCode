<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index composite pour les requêtes polymorphiques sur payments
        Schema::table('payments', function (Blueprint $table) {
            if (!$this->indexExists('payments', 'payments_payable_id_payable_type_index')) {
                $table->index(['payable_id', 'payable_type'], 'payments_payable_id_payable_type_index');
            }
            if (!$this->indexExists('payments', 'payments_status_index')) {
                $table->index('status', 'payments_status_index');
            }
        });

        // Index sur users.created_at pour les tris et statistiques
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'users_created_at_index')) {
                $table->index('created_at', 'users_created_at_index');
            }
        });

        // Index sur lesson_completions pour le suivi de progression
        if (Schema::hasTable('lesson_completions')) {
            Schema::table('lesson_completions', function (Blueprint $table) {
                if (!$this->indexExists('lesson_completions', 'lesson_completions_completed_at_index')) {
                    $table->index('completed_at', 'lesson_completions_completed_at_index');
                }
            });
        }

        // Index sur quiz_attempts.created_at
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (!$this->indexExists('quiz_attempts', 'quiz_attempts_created_at_index')) {
                    $table->index('created_at', 'quiz_attempts_created_at_index');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndexIfExists('payments_payable_id_payable_type_index');
            $table->dropIndexIfExists('payments_status_index');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists('users_created_at_index');
        });
        if (Schema::hasTable('lesson_completions')) {
            Schema::table('lesson_completions', function (Blueprint $table) {
                $table->dropIndexIfExists('lesson_completions_completed_at_index');
            });
        }
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropIndexIfExists('quiz_attempts_created_at_index');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(\Schema::getConnection()->getSchemaBuilder()->getIndexes($table))
            ->pluck('name')
            ->contains($index);
    }
};
