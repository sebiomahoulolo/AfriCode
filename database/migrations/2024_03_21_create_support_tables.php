<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->string('status')->default('open');
            $table->string('priority')->default('medium');
            $table->string('category');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_staff_reply')->default(false);
            $table->boolean('is_private')->default(false);
            $table->timestamps();
        });

        Schema::create('faq_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category');
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->integer('views_count')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->timestamps();
        });

        Schema::create('faq_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('faq_article_tag', function (Blueprint $table) {
            $table->foreignId('faq_article_id')->constrained()->onDelete('cascade');
            $table->foreignId('faq_tag_id')->constrained()->onDelete('cascade');
            $table->primary(['faq_article_id', 'faq_tag_id']);
        });

        Schema::create('faq_article_relations', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('faq_articles')->onDelete('cascade');
            $table->foreignId('related_article_id')->constrained('faq_articles')->onDelete('cascade');
            $table->primary(['article_id', 'related_article_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq_article_relations');
        Schema::dropIfExists('faq_article_tag');
        Schema::dropIfExists('faq_tags');
        Schema::dropIfExists('faq_articles');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
    }
}; 