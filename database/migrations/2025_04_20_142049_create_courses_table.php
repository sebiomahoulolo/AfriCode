<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Pour les URLs
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('duration')->nullable(); // Ex: "6 semaines", "48 heures"
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null'); // Qui donne le cours
            $table->string('image')->nullable(); // Chemin vers l'image du cours
            $table->boolean('is_published')->default(false); // Statut: publié ou brouillon
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('courses'); }
};