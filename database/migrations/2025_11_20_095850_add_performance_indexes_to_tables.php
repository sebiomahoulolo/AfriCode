<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Vérifie si un index existe déjà sur une table (compatible SQLite et MySQL)
     */
    protected function indexExists(string $table, string $indexName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list(" . $table . ")");
            foreach ($indexes as $idx) {
                if (($idx->name ?? '') === $indexName) {
                    return true;
                }
            }
            return false;
        }

        $databaseName = Schema::getConnection()->getDatabaseName();
        $result = DB::select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$databaseName, $table, $indexName]
        );
        return $result[0]->count > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Index pour la table courses
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (!$this->indexExists('courses', 'idx_courses_status')) {
                    $table->index('status', 'idx_courses_status');
                }
                if (!$this->indexExists('courses', 'idx_courses_category')) {
                    $table->index('category_id', 'idx_courses_category');
                }
                if (!$this->indexExists('courses', 'idx_courses_formateur')) {
                    $table->index('formateur_id', 'idx_courses_formateur');
                }
                if (!$this->indexExists('courses', 'idx_courses_published_at')) {
                    $table->index('published_at', 'idx_courses_published_at');
                }
                if (!$this->indexExists('courses', 'idx_courses_status_published')) {
                    $table->index(['status', 'published_at'], 'idx_courses_status_published');
                }
            });
        }

        // Index pour la table enrollments
        if (Schema::hasTable('enrollments')) {
            Schema::table('enrollments', function (Blueprint $table) {
                if (!$this->indexExists('enrollments', 'idx_enrollments_user')) {
                    $table->index('user_id', 'idx_enrollments_user');
                }
                if (!$this->indexExists('enrollments', 'idx_enrollments_course')) {
                    $table->index('course_id', 'idx_enrollments_course');
                }
                if (!$this->indexExists('enrollments', 'idx_enrollments_enrolled_at')) {
                    $table->index('enrolled_at', 'idx_enrollments_enrolled_at');
                }
                if (!$this->indexExists('enrollments', 'idx_enrollments_user_course')) {
                    $table->index(['user_id', 'course_id'], 'idx_enrollments_user_course');
                }
            });
        }

        // Index pour la table lessons
        if (Schema::hasTable('lessons')) {
            Schema::table('lessons', function (Blueprint $table) {
                if (!$this->indexExists('lessons', 'idx_lessons_module')) {
                    $table->index('module_id', 'idx_lessons_module');
                }
                if (!$this->indexExists('lessons', 'idx_lessons_order')) {
                    $table->index('order', 'idx_lessons_order');
                }
                if (!$this->indexExists('lessons', 'idx_lessons_module_order')) {
                    $table->index(['module_id', 'order'], 'idx_lessons_module_order');
                }
            });
        }

        // Index pour la table modules
        if (Schema::hasTable('modules')) {
            Schema::table('modules', function (Blueprint $table) {
                if (!$this->indexExists('modules', 'idx_modules_course')) {
                    $table->index('course_id', 'idx_modules_course');
                }
                if (!$this->indexExists('modules', 'idx_modules_order')) {
                    $table->index('order', 'idx_modules_order');
                }
            });
        }

        // Index pour la table ratings
        if (Schema::hasTable('ratings')) {
            Schema::table('ratings', function (Blueprint $table) {
                if (!$this->indexExists('ratings', 'idx_ratings_course')) {
                    $table->index('course_id', 'idx_ratings_course');
                }
                if (!$this->indexExists('ratings', 'idx_ratings_user')) {
                    $table->index('user_id', 'idx_ratings_user');
                }
                if (!$this->indexExists('ratings', 'idx_ratings_created_at')) {
                    $table->index('created_at', 'idx_ratings_created_at');
                }
            });
        }

        // Index pour la table payments
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!$this->indexExists('payments', 'idx_payments_user')) {
                    $table->index('user_id', 'idx_payments_user');
                }
                if (!$this->indexExists('payments', 'idx_payments_status')) {
                    $table->index('status', 'idx_payments_status');
                }
                if (!$this->indexExists('payments', 'idx_payments_paid_at')) {
                    $table->index('paid_at', 'idx_payments_paid_at');
                }
                if (!$this->indexExists('payments', 'idx_payments_status_paid')) {
                    $table->index(['status', 'paid_at'], 'idx_payments_status_paid');
                }
            });
        }

        // Index pour la table quiz_attempts
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (!$this->indexExists('quiz_attempts', 'idx_quiz_attempts_user')) {
                    $table->index('user_id', 'idx_quiz_attempts_user');
                }
                if (!$this->indexExists('quiz_attempts', 'idx_quiz_attempts_quiz')) {
                    $table->index('quiz_id', 'idx_quiz_attempts_quiz');
                }
                if (!$this->indexExists('quiz_attempts', 'idx_quiz_attempts_status')) {
                    $table->index('status', 'idx_quiz_attempts_status');
                }
                if (!$this->indexExists('quiz_attempts', 'idx_quiz_attempts_user_quiz')) {
                    $table->index(['user_id', 'quiz_id'], 'idx_quiz_attempts_user_quiz');
                }
            });
        }

        // Index pour la table questions
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                if (!$this->indexExists('questions', 'idx_questions_quiz')) {
                    $table->index('quiz_id', 'idx_questions_quiz');
                }
                if (!$this->indexExists('questions', 'idx_questions_order')) {
                    $table->index('order', 'idx_questions_order');
                }
            });
        }

        // Index pour la table users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!$this->indexExists('users', 'idx_users_role')) {
                    $table->index('role', 'idx_users_role');
                }
                if (!$this->indexExists('users', 'idx_users_email')) {
                    $table->index('email', 'idx_users_email');
                }
                if (!$this->indexExists('users', 'idx_users_is_active')) {
                    $table->index('is_active', 'idx_users_is_active');
                }
            });
        }

        // Index pour la table lesson_completions
        if (Schema::hasTable('lesson_completions')) {
            Schema::table('lesson_completions', function (Blueprint $table) {
                if (!$this->indexExists('lesson_completions', 'idx_lesson_completions_user')) {
                    $table->index('user_id', 'idx_lesson_completions_user');
                }
                if (!$this->indexExists('lesson_completions', 'idx_lesson_completions_lesson')) {
                    $table->index('lesson_id', 'idx_lesson_completions_lesson');
                }
                if (!$this->indexExists('lesson_completions', 'idx_lesson_completions_user_lesson')) {
                    $table->index(['user_id', 'lesson_id'], 'idx_lesson_completions_user_lesson');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les index de courses
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropIndex('idx_courses_status');
                $table->dropIndex('idx_courses_category');
                $table->dropIndex('idx_courses_formateur');
                $table->dropIndex('idx_courses_published_at');
                $table->dropIndex('idx_courses_status_published');
            });
        }

        // Supprimer les index de enrollments
        if (Schema::hasTable('enrollments')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropIndex('idx_enrollments_user');
                $table->dropIndex('idx_enrollments_course');
                $table->dropIndex('idx_enrollments_enrolled_at');
                $table->dropIndex('idx_enrollments_user_course');
            });
        }

        // Supprimer les index de lessons
        if (Schema::hasTable('lessons')) {
            Schema::table('lessons', function (Blueprint $table) {
                $table->dropIndex('idx_lessons_module');
                $table->dropIndex('idx_lessons_order');
                $table->dropIndex('idx_lessons_module_order');
            });
        }

        // Supprimer les index de modules
        if (Schema::hasTable('modules')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropIndex('idx_modules_course');
                $table->dropIndex('idx_modules_order');
            });
        }

        // Supprimer les index de ratings
        if (Schema::hasTable('ratings')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->dropIndex('idx_ratings_course');
                $table->dropIndex('idx_ratings_user');
                $table->dropIndex('idx_ratings_created_at');
            });
        }

        // Supprimer les index de payments
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex('idx_payments_user');
                $table->dropIndex('idx_payments_status');
                $table->dropIndex('idx_payments_paid_at');
                $table->dropIndex('idx_payments_status_paid');
            });
        }

        // Supprimer les index de quiz_attempts
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropIndex('idx_quiz_attempts_user');
                $table->dropIndex('idx_quiz_attempts_quiz');
                $table->dropIndex('idx_quiz_attempts_status');
                $table->dropIndex('idx_quiz_attempts_user_quiz');
            });
        }

        // Supprimer les index de questions
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropIndex('idx_questions_quiz');
                $table->dropIndex('idx_questions_order');
            });
        }

        // Supprimer les index de users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('idx_users_role');
                $table->dropIndex('idx_users_email');
                $table->dropIndex('idx_users_is_active');
            });
        }

        // Supprimer les index de lesson_completions
        if (Schema::hasTable('lesson_completions')) {
            Schema::table('lesson_completions', function (Blueprint $table) {
                $table->dropIndex('idx_lesson_completions_user');
                $table->dropIndex('idx_lesson_completions_lesson');
                $table->dropIndex('idx_lesson_completions_user_lesson');
            });
        }
    }
};
