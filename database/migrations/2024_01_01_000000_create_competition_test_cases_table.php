<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('competition_test_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->text('input')->nullable();
            $table->text('expected_output');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('competition_test_cases');
    }
}; 