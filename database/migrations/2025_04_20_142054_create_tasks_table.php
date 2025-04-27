<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->boolean('is_completed')->default(false);
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->onDelete('set null'); // Admin assigné
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tasks'); }
};