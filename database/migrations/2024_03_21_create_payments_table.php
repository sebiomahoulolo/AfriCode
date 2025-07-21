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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method');
            $table->string('payment_gateway')->nullable(); // stripe, fadapay, free, etc.
            $table->string('status');
            $table->string('transaction_id')->unique();
            $table->timestamp('payment_date');
            $table->timestamp('paid_at')->nullable();
            $table->string('refund_status')->nullable();
            $table->timestamp('refund_date')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamps();
            
            // Les contraintes de clés étrangères seront ajoutées dans une migration séparée
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}; 