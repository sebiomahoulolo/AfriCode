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
        Schema::create('mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Utilisateur mentionné
            $table->foreignId('mentioned_by_id')->constrained('users')->onDelete('cascade'); // Utilisateur qui mentionne
            $table->morphs('mentionable'); // Post ou commentaire qui contient la mention
            $table->string('mention_text'); // Le texte de la mention (@username)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index(['mentionable_type', 'mentionable_id'], 'mentions_mentionable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentions');
    }
};
