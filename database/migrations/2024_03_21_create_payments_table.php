<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('payable_id')->nullable()->index();
            $table->string('payable_type')->nullable()->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method');
            $table->string('status');
            $table->string('transaction_id')->unique();
            $table->timestamp('payment_date');
            $table->string('refund_status')->nullable();
            $table->timestamp('refund_date')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}; 