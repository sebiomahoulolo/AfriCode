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
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_promotional')->default(false)->after('status');
            $table->integer('discount_percentage')->nullable()->after('is_promotional');
            $table->decimal('original_price', 10, 2)->nullable()->after('discount_percentage');
            $table->timestamp('promotion_start_date')->nullable()->after('original_price');
            $table->timestamp('promotion_end_date')->nullable()->after('promotion_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'is_promotional',
                'discount_percentage', 
                'original_price',
                'promotion_start_date',
                'promotion_end_date'
            ]);
        });
    }
};