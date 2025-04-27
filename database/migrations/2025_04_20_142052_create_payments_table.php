<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null'); // Ou autre item payable
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF'); // FCFA par défaut
            $table->string('transaction_id')->unique()->nullable(); // ID de la transaction du fournisseur de paiement
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->string('payment_method')->nullable(); // Ex: 'stripe', 'paypal', 'mobile_money'
            $table->text('metadata')->nullable(); // Pour stocker des infos supplémentaires (JSON)
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};