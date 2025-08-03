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
        // Ajouter des champs à la table competitions
        Schema::table('competitions', function (Blueprint $table) {
            $table->integer('max_participants')->nullable()->after('level_required');
            $table->integer('entry_fee')->default(0)->after('max_participants');
            $table->json('prizes')->nullable()->after('entry_fee'); // Prix en JSON
            $table->json('judging_criteria')->nullable()->after('prizes');
            $table->boolean('is_featured')->default(false)->after('judging_criteria');
            $table->integer('views_count')->default(0)->after('is_featured');
        });

        // Ajouter des champs à la table challenges
        Schema::table('challenges', function (Blueprint $table) {
            $table->enum('difficulty', ['debutant', 'intermediaire', 'avance'])->default('debutant')->after('type');
            $table->integer('time_limit_minutes')->nullable()->after('difficulty');
            $table->json('hints')->nullable()->after('time_limit_minutes');
            $table->string('solution_template')->nullable()->after('hints');
            $table->boolean('is_featured')->default(false)->after('solution_template');
        });

        // Ajouter des champs à la table badges
        Schema::table('badges', function (Blueprint $table) {
            $table->string('color')->default('#FF8E2A')->after('icon');
            $table->boolean('is_locked')->default(false)->after('color');
            $table->integer('unlock_order')->nullable()->after('is_locked');
        });

        // Ajouter des champs à la table users pour le système de points
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0)->after('email');
            $table->integer('current_level')->default(1)->after('total_points');
            $table->integer('experience_points')->default(0)->after('current_level');
            $table->timestamp('last_activity')->nullable()->after('experience_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les champs ajoutés à competitions (si la table existe)
        if (Schema::hasTable('competitions')) {
            Schema::table('competitions', function (Blueprint $table) {
                if (Schema::hasColumn('competitions', 'max_participants')) {
                    $table->dropColumn(['max_participants', 'entry_fee', 'prizes', 'judging_criteria', 'is_featured', 'views_count']);
                }
            });
        }

        // Supprimer les champs ajoutés à challenges (si la table existe)
        if (Schema::hasTable('challenges')) {
            Schema::table('challenges', function (Blueprint $table) {
                if (Schema::hasColumn('challenges', 'difficulty')) {
                    $table->dropColumn(['difficulty', 'time_limit_minutes', 'hints', 'solution_template', 'is_featured']);
                }
            });
        }

        // Supprimer les champs ajoutés à badges (si la table existe)
        if (Schema::hasTable('badges')) {
            Schema::table('badges', function (Blueprint $table) {
                if (Schema::hasColumn('badges', 'color')) {
                    $table->dropColumn(['color', 'is_locked', 'unlock_order']);
                }
            });
        }

        // Supprimer les champs ajoutés à users (si la table existe)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'total_points')) {
                    $table->dropColumn(['total_points', 'current_level', 'experience_points', 'last_activity']);
                }
            });
        }
    }
};
