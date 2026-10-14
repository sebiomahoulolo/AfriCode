<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('job_applications', 'niveau_etudes')) {
            return;
        }
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('niveau_etudes', 100)->nullable()->after('adresse');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('niveau_etudes');
        });
    }
};
