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
        // Catégories de la base de connaissances
        Schema::create('kb_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Articles de la base de connaissances
        Schema::create('kb_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('kb_categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Commentaires sur les articles
        Schema::create('kb_article_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('kb_articles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_resolved')->default(false);
            $table->boolean('is_private')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Réponses aux commentaires
        Schema::create('kb_comment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('kb_article_comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_staff_reply')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Feedback sur les articles
        Schema::create('kb_article_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('kb_articles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['helpful', 'not_helpful'])->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // Historique des modifications
        Schema::create('kb_article_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('kb_articles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->text('changes')->nullable();
            $table->timestamps();
        });

        // Relations entre articles
        Schema::create('kb_article_relations', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('kb_articles')->onDelete('cascade');
            $table->foreignId('related_article_id')->constrained('kb_articles')->onDelete('cascade');
            $table->enum('relation_type', ['prerequisite', 'related', 'next_step'])->default('related');
            $table->primary(['article_id', 'related_article_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kb_article_relations');
        Schema::dropIfExists('kb_article_revisions');
        Schema::dropIfExists('kb_article_feedback');
        Schema::dropIfExists('kb_comment_replies');
        Schema::dropIfExists('kb_article_comments');
        Schema::dropIfExists('kb_articles');
        Schema::dropIfExists('kb_categories');
    }
};
