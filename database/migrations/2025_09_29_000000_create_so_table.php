<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('so', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Strings (DataType 27)
            $table->string('YYYY', 50)->nullable();
            $table->string('MM', 50)->nullable();
            $table->string('SO_Num', 50)->nullable();
            $table->string('STS', 50)->nullable();
            $table->string('TYPE', 50)->nullable();
            $table->string('SO_DMY', 50)->nullable();
            $table->string('AC_Num', 50)->nullable();
            $table->string('AC_NAME', 250)->nullable();
            $table->string('SLM', 50)->nullable();
            $table->string('IND', 50)->nullable();
            $table->string('AREA', 50)->nullable();
            $table->string('GROUP_', 50)->nullable();
            $table->string('PO_Num', 50)->nullable();
            $table->string('PO_DATE', 50)->nullable();
            $table->string('LOT_Num', 50)->nullable();
            $table->string('MCS_Num', 50)->nullable();
            $table->string('MODEL', 250)->nullable();
            $table->string('PRODUCT', 250)->nullable();
            $table->string('COMP_Num', 50)->nullable();
            $table->string('P_DESIGN', 50)->nullable();
            $table->string('UNIT', 50)->nullable();
            $table->string('PART_NUMBER', 50)->nullable();
            $table->string('FLUTE', 50)->nullable();
            $table->string('PQ1', 50)->nullable();
            $table->string('PQ2', 50)->nullable();
            $table->string('PQ3', 50)->nullable();
            $table->string('PQ4', 50)->nullable();
            $table->string('PQ5', 50)->nullable();
            $table->string('CURR', 50)->nullable();
            $table->string('TOTAL_LM', 50)->nullable();
            $table->string('DELIVERY_TO', 250)->nullable();
            $table->string('DELIVERY_ADD_1', 250)->nullable();
            $table->string('DELIVERY_ADD_2', 250)->nullable();
            $table->string('DELIVERY_ADD_3', 250)->nullable();
            $table->string('D_DATE_1', 50)->nullable();
            $table->string('D_TIME_1', 50)->nullable();
            $table->string('D_DUE_1', 50)->nullable();
            $table->string('D_DATE_2', 50)->nullable();
            $table->string('D_TIME_2', 50)->nullable();
            $table->string('D_DUE_2', 50)->nullable();
            $table->string('D_DATE_3', 50)->nullable();
            $table->string('D_TIME_3', 50)->nullable();
            $table->string('D_DUE_3', 50)->nullable();
            $table->string('D_DATE_4', 50)->nullable();
            $table->string('D_TIME_4', 50)->nullable();
            $table->string('D_DUE_4', 50)->nullable();
            $table->string('D_DATE_5', 50)->nullable();
            $table->string('D_TIME_5', 50)->nullable();
            $table->string('D_DUE_5', 50)->nullable();
            $table->string('D_DATE_6', 50)->nullable();
            $table->string('D_TIME_6', 50)->nullable();
            $table->string('D_DUE_6', 50)->nullable();
            $table->string('D_DATE_7', 50)->nullable();
            $table->string('D_TIME_7', 50)->nullable();
            $table->string('D_DUE_7', 50)->nullable();
            $table->string('D_DATE_8', 50)->nullable();
            $table->string('D_TIME_8', 50)->nullable();
            $table->string('D_DUE_8', 50)->nullable();
            $table->string('D_DATE_9', 50)->nullable();
            $table->string('D_TIME_9', 50)->nullable();
            $table->string('D_DUE_9', 50)->nullable();
            $table->string('D_DATE10', 50)->nullable();
            $table->string('D_TIME10', 50)->nullable();
            $table->string('D_DUE10', 50)->nullable();
            $table->string('NW_UID', 50)->nullable();
            $table->string('NW_DATE', 50)->nullable();
            $table->string('NW_TIME', 50)->nullable();
            $table->string('AM_UID', 50)->nullable();
            $table->string('AM_DATE', 50)->nullable();
            $table->string('AM_TIME', 50)->nullable();
            $table->string('CX_UID', 50)->nullable();
            $table->string('CX_DATE', 50)->nullable();
            $table->string('CX_TIME', 50)->nullable();
            $table->string('PT_UID', 50)->nullable();
            $table->string('PT_DATE', 50)->nullable();
            $table->string('PT_TIME', 50)->nullable();
            $table->string('D_LOC_Num', 50)->nullable();

            // Decimals / Numerics (DataType 12)
            $table->decimal('PER_SET', 18, 4)->nullable();
            $table->decimal('INT_L', 18, 4)->nullable();
            $table->decimal('INT_W', 18, 4)->nullable();
            $table->decimal('INT_H', 18, 4)->nullable();
            $table->decimal('EXT_L', 18, 4)->nullable();
            $table->decimal('EXT_W', 18, 4)->nullable();
            $table->decimal('EXT_H', 18, 4)->nullable();
            $table->decimal('SO_QTY', 18, 4)->nullable();
            $table->decimal('UNIT_PRICE', 18, 4)->nullable();
            $table->decimal('EX_RATE', 18, 6)->nullable();
            $table->decimal('AMOUNT', 18, 4)->nullable();
            $table->decimal('BASE_AMOUNT', 18, 4)->nullable();
            $table->decimal('MC_GROSS_M2_PER_PCS', 18, 6)->nullable();
            $table->decimal('MC_NET_M2_PER_PCS', 18, 6)->nullable();
            $table->decimal('TOTAL_SO_GROSS_M2', 18, 6)->nullable();
            $table->decimal('TOTAL_SO_NET_M2', 18, 6)->nullable();
            $table->decimal('MC_GROSS_KG_PER_PCS', 18, 6)->nullable();
            $table->decimal('MC_NET_KG_PER_PCS', 18, 6)->nullable();
            $table->decimal('TOTAL_SO_GROSS_KG', 18, 6)->nullable();
            $table->decimal('TOTAL_SO_NET_KG', 18, 6)->nullable();
            $table->decimal('D_QTY_1', 18, 4)->nullable();
            $table->decimal('D_QTY_2', 18, 4)->nullable();
            $table->decimal('D_QTY_3', 18, 4)->nullable();
            $table->decimal('D_QTY_4', 18, 4)->nullable();
            $table->decimal('D_QTY_5', 18, 4)->nullable();
            $table->decimal('D_QTY_6', 18, 4)->nullable();
            $table->decimal('D_QTY_7', 18, 4)->nullable();
            $table->decimal('D_QTY_8', 18, 4)->nullable();
            $table->decimal('D_QTY_9', 18, 4)->nullable();
            $table->decimal('D_QTY_10', 18, 4)->nullable();

            // Integers (DataType 3)
            $table->integer('TOTAL_M3')->nullable();
            $table->integer('SODateSK')->nullable();
            $table->integer('PODateSK')->nullable();

            // Long text notes (DataType 27 length 250)
            $table->string('SO_REMARK', 250)->nullable();
            $table->string('SO_INSTRUCTION_1', 250)->nullable();
            $table->string('SO_INSTRUCTION_2', 250)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('so');
    }
};


