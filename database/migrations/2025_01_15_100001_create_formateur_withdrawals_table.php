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
        Schema::create('formateur_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
            
            // Montant
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('XOF');
            
            // Méthode de paiement
            $table->enum('payment_method', ['mobile_money', 'card', 'bank_transfer'])->default('mobile_money');
            $table->string('payment_account');
            $table->string('payment_reference')->nullable();
            
            // Statut
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            
            // Traitement
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('processing_notes')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Revenus associés
            $table->json('earnings_ids')->nullable(); // IDs des formateur_earnings inclus
            
            $table->timestamps();
            
            $table->index('formateur_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_withdrawals');
    }
};

