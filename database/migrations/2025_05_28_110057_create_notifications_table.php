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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // admin.user.created, admin.course.updated, etc.
            $table->string('title');
            $table->text('message');
            $table->text('data')->nullable(); // JSON data
            $table->string('icon')->default('bell'); // Font Awesome icon
            $table->string('color')->default('primary'); // Bootstrap color: primary, success, warning, danger
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
