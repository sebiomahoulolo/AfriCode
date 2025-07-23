<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('unsubscribe_token')->nullable()->after('email');
        });

        // Générer des tokens pour les enregistrements existants
        $subscribers = \DB::table('newsletter_subscribers')->whereNull('unsubscribe_token')->get();
        foreach ($subscribers as $subscriber) {
            \DB::table('newsletter_subscribers')
                ->where('id', $subscriber->id)
                ->update(['unsubscribe_token' => Str::random(32)]);
        }

        // Ajouter la contrainte unique
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('unsubscribe_token')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn('unsubscribe_token');
        });
    }
};
