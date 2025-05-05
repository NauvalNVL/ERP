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
        Schema::create('sku_balance', function (Blueprint $table) {
            $table->id();
            $table->integer('NO');
            $table->string('CATEGORY', 100);
            $table->string('SKU', 100);
            $table->string('STS', 100);
            $table->string('SKU_NAME', 100);
            $table->string('UNIT', 100);
            $table->integer('BF_QTY');
            $table->integer('RECEIVED_QTY');
            $table->integer('RETURN_QTY');
            $table->integer('ISSUE_QTY');
            $table->integer('MISC_IN_QTY');
            $table->integer('MISC_OUT_QTY');
            $table->integer('BALANCE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_balance');
    }
}; 