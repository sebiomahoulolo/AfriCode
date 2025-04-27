<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->date('issue_date');
            $table->string('certificate_identifier')->unique(); // ID unique ou chemin fichier
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('certifications'); }
};