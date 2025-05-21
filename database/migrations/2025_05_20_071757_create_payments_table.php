<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('set null');
            $table->bigInteger('payable_id')->nullable();
            $table->string('payable_type')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->enum('payment_gateway', ['stripe', 'paypal', 'mobile_money', 'free']);
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('status', ['pending', 'succeeded', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};