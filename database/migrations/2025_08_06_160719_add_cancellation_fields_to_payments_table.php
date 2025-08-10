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
        Schema::table('payments', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('payments', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('payments', 'cancellation_reason')) {
                $table->string('cancellation_reason')->nullable()->after('cancelled_at');
            }
            if (!Schema::hasColumn('payments', 'gateway_response')) {
                $table->json('gateway_response')->nullable()->after('cancellation_reason');
            }
            if (!Schema::hasColumn('payments', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('gateway_response');
            }
            if (!Schema::hasColumn('payments', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable()->after('refund_amount');
            }
            if (!Schema::hasColumn('payments', 'refund_transaction_id')) {
                $table->string('refund_transaction_id')->nullable()->after('refunded_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'cancelled_at',
                'cancellation_reason',
                'gateway_response',
                'refund_amount',
                'refunded_at',
                'refund_transaction_id'
            ]);
        });
    }
};
