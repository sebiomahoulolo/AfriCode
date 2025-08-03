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
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('tags')->nullable();
            $table->text('learning_objectives')->nullable();
            $table->text('prerequisites')->nullable();
            $table->json('faq')->nullable();
            $table->json('testimonials')->nullable();
            $table->enum('level', ['débutant', 'intermédiaire', 'avancé', 'expert'])->default('débutant');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->decimal('price_fcfa', 12, 2)->nullable();
            $table->decimal('discounted_price', 12, 2)->nullable();
            $table->timestamp('discount_starts_at')->nullable();
            $table->timestamp('discount_ends_at')->nullable();
            $table->decimal('duration', 8, 2)->nullable();
            $table->enum('language', ['fr', 'en', 'ar', 'wo'])->default('fr');
            $table->enum('status', ['draft', 'pending_approval', 'published', 'unpublished', 'archived'])->default('draft');
            $table->boolean('is_certifying')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('cover_image_path')->nullable();
            $table->string('preview_video')->nullable();
            $table->unsignedBigInteger('formateur_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Les contraintes de clés étrangères seront ajoutées dans une migration séparée
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}; 