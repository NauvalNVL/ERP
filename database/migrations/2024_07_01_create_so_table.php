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
        Schema::create('so', function (Blueprint $table) {
            $table->id();
            $table->string('YYYY', 50);
            $table->string('MM', 50);
            $table->string('SO_Num', 50);
            $table->string('STS', 50);
            $table->string('TYPE', 50);
            $table->string('SO_DMY', 50);
            $table->string('AC_Num', 50);
            $table->string('AC_NAME', 250);
            $table->string('SLM', 50);
            $table->string('IND', 50);
            $table->string('AREA', 50);
            $table->string('GROUP_', 50);
            $table->string('PO_Num', 50);
            $table->string('PO_DATE', 50);
            $table->string('LOT_Num', 50);
            $table->string('MCS_Num', 50);
            $table->string('MODEL', 250);
            $table->string('PRODUCT', 250);
            $table->string('COMP_Num', 50);
            $table->string('P_DESIGN', 50);
            $table->float('PER_SET');
            $table->string('UNIT', 50);
            $table->string('PART_NUMBER', 50);
            $table->float('INT_L');
            $table->float('INT_W');
            $table->float('INT_H');
            $table->float('EXT_L');
            $table->float('EXT_W');
            $table->float('EXT_H');
            $table->string('FLUTE', 50);
            $table->string('PQ1', 50);
            $table->string('PQ2', 50);
            $table->string('PQ3', 50);
            $table->string('PQ4', 50);
            $table->string('PQ5', 50);
            $table->float('SO_QTY');
            $table->float('UNIT_PRICE');
            $table->string('CURR', 50);
            $table->float('EX_RATE');
            $table->float('AMOUNT');
            $table->float('BASE_AMOUNT');
            $table->float('MC_GROSS_M2_PER_PCS');
            $table->float('MC_NET_M2_PER_PCS');
            $table->float('TOTAL_SO_GROSS_M2');
            $table->float('TOTAL_SO_NET_M2');
            $table->string('TOTAL_LM', 50);
            $table->integer('TOTAL_M3');
            $table->float('MC_GROSS_KG_PER_PCS');
            $table->float('MC_NET_KG_PER_PCS');
            $table->float('TOTAL_SO_GROSS_KG');
            $table->float('TOTAL_SO_NET_KG');
            $table->string('SO_REMARK', 250);
            $table->string('SO_INSTRUCTION_1', 250);
            $table->string('SO_INSTRUCTION_2', 250);
            $table->string('D_LOC_Num', 50);
            $table->string('DELIVERY_TO', 250);
            $table->string('DELIVERY_ADD_1', 250);
            $table->string('DELIVERY_ADD_2', 250);
            $table->string('DELIVERY_ADD_3', 250);
            $table->string('D_DATE_1', 50);
            $table->string('D_TIME_1', 50);
            $table->string('D_DUE_1', 50);
            $table->float('D_QTY_1');
            $table->string('D_DATE_2', 50);
            $table->string('D_TIME_2', 50);
            $table->string('D_DUE_2', 50);
            $table->float('D_QTY_2');
            $table->string('D_DATE_3', 50);
            $table->string('D_TIME_3', 50);
            $table->string('D_DUE_3', 50);
            $table->float('D_QTY_3');
            $table->string('D_DATE_4', 50);
            $table->string('D_TIME_4', 50);
            $table->string('D_DUE_4', 50);
            $table->float('D_QTY_4');
            $table->string('D_DATE_5', 50);
            $table->string('D_TIME_5', 50);
            $table->string('D_DUE_5', 50);
            $table->float('D_QTY_5');
            $table->string('D_DATE_6', 50);
            $table->string('D_TIME_6', 50);
            $table->string('D_DUE_6', 50);
            $table->float('D_QTY_6');
            $table->string('D_DATE_7', 50);
            $table->string('D_TIME_7', 50);
            $table->string('D_DUE_7', 50);
            $table->float('D_QTY_7');
            $table->string('D_DATE_8', 50);
            $table->string('D_TIME_8', 50);
            $table->string('D_DUE_8', 50);
            $table->float('D_QTY_8');
            $table->string('D_DATE_9', 50);
            $table->string('D_TIME_9', 50);
            $table->string('D_DUE_9', 50);
            $table->float('D_QTY_9');
            $table->string('D_DATE10', 50);
            $table->string('D_TIME10', 50);
            $table->string('D_DUE10', 50);
            $table->float('D_QTY_10');
            $table->string('NW_UID', 50);
            $table->string('NW_DATE', 50);
            $table->string('NW_TIME', 50);
            $table->string('AM_UID', 50);
            $table->string('AM_DATE', 50);
            $table->string('AM_TIME', 50);
            $table->string('CX_UID', 50);
            $table->string('CX_DATE', 50);
            $table->string('CX_TIME', 50);
            $table->string('PT_UID', 50);
            $table->string('PT_DATE', 50);
            $table->string('PT_TIME', 50);
            $table->integer('SODateSK');
            $table->integer('PODateSK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so');
    }
}; 