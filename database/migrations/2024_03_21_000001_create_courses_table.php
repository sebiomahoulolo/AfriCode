<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('prerequisites')->nullable();
            $table->json('faq')->nullable();
            $table->json('testimonials')->nullable();
            $table->enum('level', ['debutant', 'intermediaire', 'avance', 'tous_niveaux'])->default('debutant');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', ['draft', 'pending_approval', 'published', 'unpublished', 'archived'])->default('draft');
            $table->string('cover_image_path')->nullable();
            $table->unsignedBigInteger('formateur_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}; 