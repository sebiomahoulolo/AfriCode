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
        // Contraintes pour la table courses
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('formateur_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });

        // Contraintes pour la table quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });

        // Contraintes pour la table payments
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les contraintes dans l'ordre inverse
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['enrollment_id']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropForeign(['course_id']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['formateur_id']);
            $table->dropForeign(['category_id']);
        });
    }
};
