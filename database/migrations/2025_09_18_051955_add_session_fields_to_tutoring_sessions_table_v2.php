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
        // Ajouter les colonnes une par une avec vérification
        if (!Schema::hasColumn('tutoring_sessions', 'session_code')) {
            Schema::table('tutoring_sessions', function (Blueprint $table) {
                $table->string('session_code', 8)->nullable()->after('meeting_link');
            });
        }

        if (!Schema::hasColumn('tutoring_sessions', 'feedback')) {
            Schema::table('tutoring_sessions', function (Blueprint $table) {
                $table->text('feedback')->nullable()->after('notes');
            });
        }

        if (!Schema::hasColumn('tutoring_sessions', 'started_at')) {
            Schema::table('tutoring_sessions', function (Blueprint $table) {
                $table->timestamp('started_at')->nullable()->after('feedback');
            });
        }

        if (!Schema::hasColumn('tutoring_sessions', 'completed_at')) {
            Schema::table('tutoring_sessions', function (Blueprint $table) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            });
        }

        if (!Schema::hasColumn('tutoring_sessions', 'cancelled_at')) {
            Schema::table('tutoring_sessions', function (Blueprint $table) {
                $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            });
        }

        if (!Schema::hasColumn('tutoring_sessions', 'cancellation_reason')) {
            Schema::table('tutoring_sessions', function (Blueprint $table) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            });
        }

        // Remplir les codes existants et ajouter la contrainte unique
        if (Schema::hasColumn('tutoring_sessions', 'session_code')) {
            // Remplir les codes vides (syntaxe compatible SQLite et MySQL)
            $driver = DB::connection()->getDriverName();
            if ($driver === 'sqlite') {
                DB::statement("UPDATE tutoring_sessions SET session_code = 'TEMP' || substr('0000' || id, -4) WHERE session_code IS NULL OR session_code = ''");
            } else {
                DB::statement('UPDATE tutoring_sessions SET session_code = CONCAT("TEMP", LPAD(id, 4, "0")) WHERE session_code IS NULL OR session_code = ""');
            }
            
            // Ajouter la contrainte unique
            try {
                Schema::table('tutoring_sessions', function (Blueprint $table) {
                    $table->unique('session_code');
                });
            } catch (Exception $e) {
                // La contrainte existe peut-être déjà
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutoring_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'session_code',
                'feedback',
                'started_at',
                'completed_at',
                'cancelled_at',
                'cancellation_reason'
            ]);
        });
    }
};