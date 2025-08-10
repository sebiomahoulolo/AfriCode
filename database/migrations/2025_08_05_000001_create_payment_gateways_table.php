<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // stripe, paypal, orange_money, etc.
            $table->string('slug')->unique();
            $table->string('display_name'); // Nom affiché
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // classe CSS d'icône
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->json('supported_currencies')->nullable(); // ['USD', 'EUR', 'XOF']
            $table->json('configuration')->nullable(); // Clés API, secrets, etc.
            $table->boolean('test_mode')->default(true);
            $table->integer('order_priority')->default(0); // Ordre d'affichage
            $table->decimal('min_amount', 10, 2)->nullable(); // Montant minimum
            $table->decimal('max_amount', 10, 2)->nullable(); // Montant maximum
            $table->decimal('fees_percentage', 5, 2)->default(0); // Frais en pourcentage
            $table->decimal('fees_fixed', 10, 2)->default(0); // Frais fixes
            $table->text('success_message')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Index pour les requêtes courantes
            $table->index(['is_active', 'order_priority']);
            $table->index('is_default');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
};
