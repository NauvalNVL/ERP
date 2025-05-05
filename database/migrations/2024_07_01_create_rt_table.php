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
        Schema::create('rt', function (Blueprint $table) {
            $table->id();
            $table->string('YYYY', 50);
            $table->string('MM', 50);
            $table->string('RT', 50);
            $table->string('RT_DATE', 50);
            $table->string('RC', 50);
            $table->string('VENDOR_CODE', 50);
            $table->string('VENDOR_NAME', 50);
            $table->string('VENDOR_CN', 50);
            $table->string('VENDOR_CN_DATE', 50);
            $table->string('VENDOR_PO', 50);
            $table->string('VENDOR_RC_DATE', 50);
            $table->string('ITEM', 50);
            $table->string('SKU', 50);
            $table->string('SKU_NAME', 150);
            $table->string('UNIT', 50);
            $table->float('RECEIVE_QTY');
            $table->float('RETURN_QTY');
            $table->string('LOT', 50);
            $table->string('CURR', 50);
            $table->string('EXRATE', 50);
            $table->string('UNIT_PRICE', 50);
            $table->float('GROSS_AMT');
            $table->string('TAX_CODE_1', 50);
            $table->float('TAX_1');
            $table->float('TAX_AMT_1');
            $table->float('TAX_ADJ_1');
            $table->float('NET_TAX_1');
            $table->string('TAX_CODE_2', 50);
            $table->float('TAX_2');
            $table->float('TAX_AMT_2');
            $table->float('TAX_ADJ_2');
            $table->float('NET_TAX_2');
            $table->string('TAX_CODE_3', 50);
            $table->float('TAX_3');
            $table->float('TAX_AMT_3');
            $table->float('TAX_ADJ_3');
            $table->float('NET_TAX_3');
            $table->float('NET_AMT');
            $table->integer('RTDateSk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rt');
    }
}; 