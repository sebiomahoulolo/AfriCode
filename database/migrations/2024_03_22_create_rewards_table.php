<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('type'); // monetary, points, discount, etc.
            $table->json('requirements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('reward_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_claimed')->default(false);
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['reward_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reward_user');
        Schema::dropIfExists('rewards');
    }
}; 