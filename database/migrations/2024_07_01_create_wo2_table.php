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
        Schema::create('wo2', function (Blueprint $table) {
            $table->id();
            $table->string('WO_Num', 50);
            $table->string('WO_STS', 50);
            $table->string('WOType', 50);
            $table->string('RerunCode', 50);
            $table->string('DODueDate', 50);
            $table->string('AC_Num', 50);
            $table->string('AC_Name', 250);
            $table->string('SLM', 50);
            $table->string('IND', 50);
            $table->string('AREA', 50);
            $table->string('GROUP_', 50);
            $table->string('SO_Num', 50);
            $table->string('SO_PO_Num', 50);
            $table->string('PO_Date', 50);
            $table->string('SO_LOT_Num', 50);
            $table->string('MCS_Num', 50);
            $table->string('Model', 250);
            $table->string('Product', 250);
            $table->string('COMP', 50);
            $table->string('P_Design', 50);
            $table->float('Per_Set');
            $table->string('Part_Number', 50);
            $table->float('INT_L');
            $table->float('INT_W');
            $table->float('INT_H');
            $table->float('EXT_L');
            $table->float('EXT_W');
            $table->float('EXT_H');
            $table->string('Flute', 50);
            $table->string('WO_PQ1', 50);
            $table->string('WO_PQ2', 50);
            $table->string('WO_PQ3', 50);
            $table->string('WO_PQ4', 50);
            $table->string('WO_PQ5', 50);
            $table->float('Release_Qty');
            $table->float('Book_Qty');
            $table->float('Allow_Qty');
            $table->float('WO_Run_Qty');
            $table->float('Sheet_Length');
            $table->float('Sheet_Width');
            $table->float('Roll_Size');
            $table->float('Out');
            $table->float('CV_Out1');
            $table->float('CV_Out2');
            $table->float('Joint_');
            $table->string('RunMethod', 100);
            $table->float('M2_Per_Pcs');
            $table->float('KG_Per_Pcs');
            $table->string('WO_Instruction_1', 250);
            $table->string('WO_Instruction_2', 250);
            $table->string('WO_Instruction_3', 250);
            $table->string('MSP1_MCH', 50);
            $table->float('MSP1_Qty_In');
            $table->float('MSP1_UP');
            $table->float('Joint1');
            $table->float('MSP1_Qty_Out');
            $table->string('MSP2_MCH', 50);
            $table->float('MSP2_Qty_In');
            $table->float('MSP2_UP');
            $table->float('Joint2');
            $table->float('MSP2_QTY_Out');
            $table->string('MSP3_MCH', 50);
            $table->float('MSP3_Qty_In');
            $table->float('MSP3_UP');
            $table->float('Joint3');
            $table->float('MSP3_Qty_Out');
            $table->string('MSP4_MCH', 50);
            $table->float('MSP4_Qty_In');
            $table->float('MSP4_UP');
            $table->float('Joint4');
            $table->float('MSP4_Qty_Out');
            $table->string('MSP5_MCH', 50);
            $table->float('MSP5_Qty_In');
            $table->float('MSP5_UP');
            $table->float('Joint5');
            $table->float('MSP5_Qty_Out');
            $table->string('MSP6_MCH', 50);
            $table->float('MSP6_Qty_In');
            $table->float('MSP6_UP');
            $table->float('Joint6');
            $table->float('MSP6_Qty_Out');
            $table->string('MSP7_MCH', 50);
            $table->float('MSP7_Qty_In');
            $table->float('MSP7_UP');
            $table->float('Joint7');
            $table->float('MSP7_Qty_Out');
            $table->string('MSP8_MCH', 50);
            $table->float('MSP8_Qty_In');
            $table->float('MSP8_UP');
            $table->float('Joint8');
            $table->float('MSP8_Qty_Out');
            $table->string('MC_SPECIAL_INST1', 150);
            $table->string('MC_SPECIAL_INST2', 150);
            $table->string('MC_SPECIAL_INST3', 150);
            $table->string('MC_SPECIAL_INST4', 150);
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
            $table->integer('DODueDateSK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo2');
    }
}; 