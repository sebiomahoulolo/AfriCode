<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('push_campaigns')) {
            return;
        }
        Schema::create('push_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('url', 512)->default('/');
            $table->string('icon', 512)->default('/images/icons/africode-192.png');
            $table->string('tag', 100)->default('africode-campaign');
            // all | role | course_enrolled | inactive_days
            $table->string('target_type', 30)->default('all');
            // JSON: pour role → {"role":"apprenant"}, course → {"course_id":5}, inactive → {"days":7}
            $table->json('target_value')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            // draft | scheduled | sending | sent | failed
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_campaigns');
    }
};
