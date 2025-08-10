<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes pour les passerelles de paiement
            $table->foreignId('payment_gateway_id')->nullable()->after('enrollment_id')->constrained('payment_gateways')->onDelete('set null');
            $table->decimal('fees', 10, 2)->default(0)->after('currency');
            $table->decimal('total_amount', 10, 2)->nullable()->after('fees');
            $table->string('external_transaction_id')->nullable()->after('transaction_id');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_date');
            $table->json('metadata')->nullable()->after('payment_details');
            $table->text('failure_reason')->nullable()->after('metadata');
            $table->timestamp('webhook_received_at')->nullable()->after('failure_reason');
            
            // Modifier les colonnes existantes si nécessaire
            $table->string('status')->default('pending')->change();
            
            // Ajouter des index pour améliorer les performances
            $table->index(['status', 'created_at']);
            $table->index(['payment_gateway', 'status']);
            $table->index('external_transaction_id');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_gateway_id']);
            $table->dropColumn([
                'payment_gateway_id',
                'fees',
                'total_amount',
                'external_transaction_id',
                'refund_amount',
                'metadata',
                'failure_reason',
                'webhook_received_at'
            ]);
            
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['payment_gateway', 'status']);
            $table->dropIndex(['external_transaction_id']);
        });
    }
};
