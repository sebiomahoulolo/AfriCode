<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Importez DB si vous utilisez DB::raw() pour des valeurs par défaut spécifiques

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('rules')->nullable();
            $table->timestamp('start_datetime')->nullable();
            $table->timestamp('end_datetime')->nullable(); // Correction principale ici
            $table->timestamp('registration_deadline')->nullable();
            $table->enum('level_required', ['debutant', 'intermediaire', 'avance', 'tous_niveaux'])->default('tous_niveaux');
            
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade'); 
            
            $table->enum('status', [
                'draft', 
                'upcoming', 
                'open_for_registration', 
                'in_progress', 
                'judging', 
                'completed', 
                'archived'
            ])->default('draft');
            $table->string('cover_image_path')->nullable();
            $table->timestamps(); // Crée created_at et updated_at (nullables par défaut)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};