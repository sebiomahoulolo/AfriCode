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
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade')->unique();
            $table->timestamp('issued_at')->useCurrent();
            $table->string('certificate_path');
            $table->string('verification_code')->unique();
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('certifications'); }
};