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
        Schema::create('sku_listing', function (Blueprint $table) {
            $table->id();
            $table->string('NO', 50);
            $table->string('SKU', 100);
            $table->string('STS', 100);
            $table->string('SKU_NAME', 100);
            $table->string('TYPE', 100);
            $table->string('CATEGORY', 100);
            $table->string('REPORT_GROUP', 100);
            $table->string('BASE_UNIT', 100);
            $table->integer('VALUATION_QTY');
            $table->integer('UNIT');
            $table->integer('DAY_SHIP');
            $table->integer('UNIT_SHIPPED');
            $table->integer('LOCATION');
            $table->integer('MIN_LEVEL');
            $table->integer('MAX_LEVEL');
            $table->integer('REORDER_LEVEL');
            $table->string('S', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_listing');
    }
}; 