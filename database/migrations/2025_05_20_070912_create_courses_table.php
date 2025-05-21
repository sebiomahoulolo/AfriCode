<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->enum('level', ['debutant', 'intermediaire', 'avance', 'tous_niveaux'])->default('debutant');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('EUR');
            $table->string('cover_image_path')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'published', 'unpublished', 'archived'])->default('draft');
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
        });
    }
    
    public function down(): void { 
        Schema::dropIfExists('courses'); 
    }
};