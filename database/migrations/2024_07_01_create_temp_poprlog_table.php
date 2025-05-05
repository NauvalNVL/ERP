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
        Schema::create('temp_poprlog', function (Blueprint $table) {
            $table->id();
            $table->float('ID');
            $table->string('PO', 255);
            $table->string('PO_STS', 255);
            $table->string('PO_DATE', 255);
            $table->integer('AP_AC');
            $table->string('AP_NAME', 255);
            $table->string('TRAN', 255);
            $table->string('TXG', 255);
            $table->string('BUYER', 255);
            $table->string('RD', 255);
            $table->string('PO_CURR', 255);
            $table->float('EX_RATE');
            $table->float('MTD');
            $table->string('TYPE', 255);
            $table->float('NO');
            $table->float('SUB');
            $table->string('SKU', 255);
            $table->string('SKU_NAME', 255);
            $table->float('QTY');
            $table->string('UOM', 255);
            $table->float('UP');
            $table->float('DIS');
            $table->string('DT', 255);
            $table->float('NET_UP');
            $table->float('AMT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_poprlog');
    }
}; 