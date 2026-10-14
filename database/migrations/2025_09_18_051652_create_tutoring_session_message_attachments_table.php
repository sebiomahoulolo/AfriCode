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
        if (!Schema::hasTable('tutoring_session_message_attachments')) {
            Schema::create('tutoring_session_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('tutoring_session_messages')->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->bigInteger('size');
            $table->string('mime_type');
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoring_session_message_attachments');
    }
};