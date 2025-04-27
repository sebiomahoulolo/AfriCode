<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null'); // Peut être système
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade'); // Admin généralement
            $table->string('subject');
            $table->text('body');
            $table->timestamp('read_at')->nullable(); // Si le destinataire l'a lu
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('messages'); }
};