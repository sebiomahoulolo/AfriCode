<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cette migration est conservée pour préserver l'historique.
     * La colonne 'status' est déjà gérée par 2025_07_19_120159.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('quiz_attempts', 'status')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->string('status')->default('completed')->after('score');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('quiz_attempts', 'status')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
