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
        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->integer('watch_time_seconds')->default(0)->after('completed_at');
            $table->boolean('can_complete')->default(false)->after('watch_time_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->dropColumn(['watch_time_seconds', 'can_complete']);
        });
    }
};
