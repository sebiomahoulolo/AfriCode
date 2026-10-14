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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_attempts', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('submitted_at');
            }
            
            if (!Schema::hasColumn('quiz_attempts', 'passed')) {
                $table->boolean('passed')->default(false)->after('score');
            }
            
            if (!Schema::hasColumn('quiz_attempts', 'enrollment_id')) {
                $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('cascade')->after('quiz_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempts', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            
            if (Schema::hasColumn('quiz_attempts', 'passed')) {
                $table->dropColumn('passed');
            }
            
            if (Schema::hasColumn('quiz_attempts', 'enrollment_id')) {
                $table->dropForeign(['enrollment_id']);
                $table->dropColumn('enrollment_id');
            }
        });
    }
};