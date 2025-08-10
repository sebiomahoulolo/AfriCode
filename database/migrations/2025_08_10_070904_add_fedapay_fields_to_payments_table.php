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
            if (!Schema::hasColumn('payments', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('payments', 'converted_amount')) {
                $table->decimal('converted_amount', 10, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'converted_currency')) {
                $table->string('converted_currency', 3)->nullable()->after('currency');
            }
            // gateway_response existe déjà grâce à une migration précédente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'gateway_transaction_id',
                'converted_amount',
                'converted_currency'
            ]);
        });
    }
};
