<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // ── Fix 1 : notifications.title et .message nullable ──────────────────
        // Laravel DatabaseChannel insère sans ces colonnes → MySQL rejette.
        // Les rendre nullable résout le conflit sans casser les notifications custom.
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (Schema::hasColumn('notifications', 'title')) {
                    $table->string('title')->nullable()->default(null)->change();
                }
                if (Schema::hasColumn('notifications', 'message')) {
                    $table->text('message')->nullable()->default(null)->change();
                }
            });
        }

        // ── Fix 2 : unsubscribe_token dans newsletter_subscribers ─────────────
        // Si la migration précédente n'a pas tourné en prod, la colonne manque.
        if (Schema::hasTable('newsletter_subscribers')) {
            if (!Schema::hasColumn('newsletter_subscribers', 'unsubscribe_token')) {
                Schema::table('newsletter_subscribers', function (Blueprint $table) {
                    $table->string('unsubscribe_token', 64)->nullable()->after('email');
                });

                // Générer les tokens manquants
                \DB::table('newsletter_subscribers')
                    ->whereNull('unsubscribe_token')
                    ->get()
                    ->each(function ($sub) {
                        \DB::table('newsletter_subscribers')
                            ->where('id', $sub->id)
                            ->update(['unsubscribe_token' => Str::random(32)]);
                    });

                Schema::table('newsletter_subscribers', function (Blueprint $table) {
                    $table->string('unsubscribe_token', 64)->unique()->change();
                });
            }
        }
    }

    public function down(): void
    {
        // Rétablir title et message NOT NULL
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('title')->nullable(false)->change();
                $table->text('message')->nullable(false)->change();
            });
        }
    }
};
