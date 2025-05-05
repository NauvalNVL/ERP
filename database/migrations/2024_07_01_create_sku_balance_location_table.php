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
        Schema::create('sku_balance_location', function (Blueprint $table) {
            $table->integer('ID')->primary();
            $table->string('NO', 100);
            $table->string('CATEGORY', 100);
            $table->string('SKU', 100);
            $table->string('STS', 100);
            $table->string('SKU_NAME', 100);
            $table->string('UNIT', 100);
            $table->string('B_F_QTY', 100);
            $table->string('RECEIVED_QTY', 100);
            $table->string('RETURN_QTY', 100);
            $table->string('BALANCE', 100);
            $table->string('LOC', 100);
            $table->string('LOCBAL', 100);
            $table->integer('STOCK_DateSK');
            $table->dateTime('STOCK_Date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_balance_location');
    }
}; 