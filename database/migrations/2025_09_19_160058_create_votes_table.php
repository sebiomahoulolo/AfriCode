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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('voteable'); // Post ou commentaire voté
            $table->enum('type', ['upvote', 'downvote'])->default('upvote');
            $table->timestamps();
            
            // Un utilisateur ne peut voter qu'une fois par post/commentaire
            $table->unique(['user_id', 'voteable_type', 'voteable_id']);
            $table->index(['voteable_type', 'voteable_id'], 'votes_voteable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
