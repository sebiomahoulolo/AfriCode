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
        if (Schema::hasTable('formateur_earnings')) {
            return;
        }

        Schema::create('formateur_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->onDelete('set null');
            
            // Montants
            $table->decimal('course_price', 10, 2); // Prix du cours
            $table->decimal('formateur_share', 10, 2); // 77% du prix
            $table->decimal('africode_share', 10, 2); // 23% du prix
            $table->decimal('bonus_amount', 10, 2)->default(0); // Bonus si applicable
            
            // Calcul
            $table->integer('student_count')->default(0); // Nombre d'étudiants pour ce calcul
            $table->decimal('total_earnings', 12, 2); // Total gagné (formateur_share + bonus)
            
            // Paiement
            $table->enum('payment_status', ['pending', 'paid', 'processing', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); // mobile_money, card
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Période
            $table->date('period_start');
            $table->date('period_end');
            
            $table->timestamps();
            
            $table->index('formateur_id');
            $table->index('course_id');
            $table->index('payment_status');
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_earnings');
    }
};
