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
        Schema::create('mm_skus', function (Blueprint $table) {
            $table->string('sku', 20)->primary();
            $table->string('sts', 10)->nullable();
            $table->string('sku_name', 150);
            $table->string('category_code', 20);
            $table->foreign('category_code')->references('code')->on('mm_categories');
            $table->string('type', 10)->nullable();
            $table->string('uom', 10)->nullable();
            $table->decimal('boh', 15, 3)->default(0);
            $table->decimal('fpo', 15, 3)->default(0);
            $table->decimal('rol', 15, 3)->default(0);
            $table->integer('total_part')->default(0);
            $table->decimal('min_qty', 15, 2)->default(0);
            $table->decimal('max_qty', 15, 2)->default(0);
            $table->string('additional_name', 200)->nullable();
            $table->string('part_number1', 100)->nullable();
            $table->string('part_number2', 100)->nullable();
            $table->string('part_number3', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_skus');
    }
}; 