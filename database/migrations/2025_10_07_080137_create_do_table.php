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
        Schema::create('DO', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('DOYYYY', 50)->nullable()->collation($collation);
            $table->string('DOMM', 50)->nullable()->collation($collation);
            $table->string('DO_Num', 50)->nullable()->collation($collation);
            $table->string('Status', 50)->nullable()->collation($collation);
            $table->string('DO_DMY', 50)->nullable()->collation($collation);
            $table->string('DO_VHC_Num', 50)->nullable()->collation($collation);
            $table->string('VHC_Class', 50)->nullable()->collation($collation);
            $table->string('AC_Num', 50)->nullable()->collation($collation);
            $table->string('AC_Name', 250)->nullable()->collation($collation);
            $table->string('No', 50)->nullable()->collation($collation);
            $table->string('MCS_Num', 50)->nullable()->collation($collation);
            $table->string('Model', 250)->nullable()->collation($collation);
            $table->string('Product', 50)->nullable()->collation($collation);
            $table->string('COMP', 50)->nullable()->collation($collation);
            $table->string('PD', 50)->nullable()->collation($collation);
            $table->decimal('PCS_PER_SET', 15, 2)->nullable();
            $table->string('Unit', 50)->nullable()->collation($collation);
            $table->string('Part_Number', 50)->nullable()->collation($collation);
            $table->decimal('INT_L', 15, 2)->nullable();
            $table->decimal('INT_W', 15, 2)->nullable();
            $table->decimal('INT_H', 15, 2)->nullable();
            $table->decimal('EXT_L', 15, 2)->nullable();
            $table->decimal('EXT_W', 15, 2)->nullable();
            $table->decimal('EXT_H', 15, 2)->nullable();
            $table->string('Flute', 50)->nullable()->collation($collation);
            $table->string('SLM', 50)->nullable()->collation($collation);
            $table->string('IND', 50)->nullable()->collation($collation);
            $table->string('Area1', 50)->nullable()->collation($collation);
            $table->string('Del_Code', 30)->nullable()->collation($collation);
            $table->string('Group_', 50)->nullable()->collation($collation);
            $table->string('SO_Num', 50)->nullable()->collation($collation);
            $table->string('SO_Type', 50)->nullable()->collation($collation);
            $table->string('PO_Num', 250)->nullable()->collation($collation);
            $table->string('PO_Date', 50)->nullable()->collation($collation);
            $table->string('LOT_Num', 50)->nullable()->collation($collation);
            $table->string('PQ1', 50)->nullable()->collation($collation);
            $table->string('PQ2', 50)->nullable()->collation($collation);
            $table->string('PQ3', 50)->nullable()->collation($collation);
            $table->string('PQ4', 50)->nullable()->collation($collation);
            $table->string('PQ5', 50)->nullable()->collation($collation);
            $table->decimal('DO_Qty', 15, 2)->nullable();
            $table->string('UNAPPLIED_FG', 10)->nullable()->collation($collation);
            $table->decimal('DO_M3', 15, 2)->nullable();
            $table->decimal('SO_Unit_Price', 15, 2)->nullable();
            $table->string('Curr', 50)->nullable()->collation($collation);
            $table->decimal('Ex_Rate', 15, 2)->nullable();
            $table->decimal('DO_Tran_Amt', 15, 2)->nullable();
            $table->decimal('DO_Base_Amt', 15, 2)->nullable();
            $table->decimal('MC_Gross_M2_Per_Pcs', 15, 2)->nullable();
            $table->decimal('MC_Net_M2_Per_Pcs', 15, 2)->nullable();
            $table->decimal('Total_DO_Gross_M2', 15, 2)->nullable();
            $table->decimal('Total_DO_Net_M2', 15, 2)->nullable();
            $table->decimal('MC_Gross_Kg_Per_Pcs', 15, 2)->nullable();
            $table->string('MC_Net_Kg_Per_Pcs', 30)->nullable()->collation($collation);
            $table->decimal('Total_DO_Gross_KG', 15, 2)->nullable();
            $table->decimal('Total_DO_Net_KG', 15, 2)->nullable();
            $table->integer('DODateSK')->nullable();
            $table->string('DO_Remark1', 250)->nullable()->collation($collation);
            $table->string('DO_Remark2', 250)->nullable()->collation($collation);
            $table->string('Cancelled_Reason', 250)->nullable()->collation($collation);
        });

        // Relasi DO ke master vehicle berdasarkan nomor kendaraan
        Schema::table('DO', function (Blueprint $table) {
            $table->foreign('DO_VHC_Num')
                  ->references('VEHICLE_NO')
                  ->on('vehicle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DO', function (Blueprint $table) {
            $table->dropForeign(['DO_VHC_Num']);
        });

        Schema::dropIfExists('do');
    }
};
