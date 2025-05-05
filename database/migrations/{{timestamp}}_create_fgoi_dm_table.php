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
        Schema::create('FGOI_DM', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('NO_', 50)->nullable()->collation($collation);
            $table->string('AC_NUM', 100)->nullable()->collation($collation);
            $table->string('AC_NAME', 100)->nullable()->collation($collation);
            $table->string('MCS', 100)->nullable()->collation($collation);
            $table->string('MODEL', 100)->nullable()->collation($collation);
            $table->string('PRODUCT', 100)->nullable()->collation($collation);
            $table->string('COMP', 100)->nullable()->collation($collation);
            $table->string('PD', 100)->nullable()->collation($collation);
            $table->string('PCS_PER_SET', 100)->nullable()->collation($collation);
            $table->string('UNIT', 100)->nullable()->collation($collation);
            $table->string('PART_NUMBER', 100)->nullable()->collation($collation);
            $table->decimal('INT_L', 15, 2)->nullable(); // DataType 12
            $table->decimal('INT_W', 15, 2)->nullable(); // DataType 12
            $table->decimal('INT_H', 15, 2)->nullable(); // DataType 12
            $table->decimal('EXT_L', 15, 2)->nullable(); // DataType 12
            $table->decimal('EXT_W', 15, 2)->nullable(); // DataType 12
            $table->decimal('EXT_H', 15, 2)->nullable(); // DataType 12
            $table->string('FLUTE', 100)->nullable()->collation($collation);
            $table->string('SO_NUM', 100)->nullable()->collation($collation);
            $table->string('SO_TYPE', 100)->nullable()->collation($collation);
            $table->string('SLM', 100)->nullable()->collation($collation);
            $table->string('IND', 100)->nullable()->collation($collation);
            $table->string('AREA', 100)->nullable()->collation($collation);
            $table->string('GROUP_', 100)->nullable()->collation($collation); // Note trailing underscore
            $table->string('PO_NUM', 100)->nullable()->collation($collation);
            $table->string('PO_DATE', 100)->nullable()->collation($collation);
            $table->string('LOT', 100)->nullable()->collation($collation);
            $table->string('PQ1', 100)->nullable()->collation($collation);
            $table->string('PQ2', 100)->nullable()->collation($collation);
            $table->string('PQ3', 100)->nullable()->collation($collation);
            $table->string('PQ4', 100)->nullable()->collation($collation);
            $table->string('PQ5', 100)->nullable()->collation($collation);
            $table->string('YYYY', 100)->nullable()->collation($collation);
            $table->string('MM', 100)->nullable()->collation($collation);
            $table->string('WO_NUM', 100)->nullable()->collation($collation);
            $table->string('FG_TYPE', 100)->nullable()->collation($collation);
            $table->string('FG_TYPE_NAME', 100)->nullable()->collation($collation);
            $table->string('GRX', 100)->nullable()->collation($collation);
            $table->string('FG_PROCESS_DATE', 100)->nullable()->collation($collation);
            $table->string('FG_RECEIVE_DATE', 100)->nullable()->collation($collation);
            $table->string('FG_REF', 100)->nullable()->collation($collation);
            $table->decimal('FG_QTY', 15, 2)->nullable(); // DataType 12
            $table->string('FG_LOC', 100)->nullable()->collation($collation);
            $table->decimal('MC_GROSS_M2_PER_PCS', 15, 2)->nullable(); // DataType 12
            $table->decimal('MC_NET_PER_PCS', 15, 2)->nullable(); // DataType 12
            $table->decimal('TOTAL_FG_GROSS_M2', 15, 2)->nullable(); // DataType 12
            $table->decimal('TOTAL_FG_NET_M2', 15, 2)->nullable(); // DataType 12
            $table->decimal('MC_GROSS_KG_PER_PCS', 15, 2)->nullable(); // DataType 12
            $table->decimal('MC_NET_KG_PER_PCS', 15, 2)->nullable(); // DataType 12
            $table->decimal('TOTAL_FG_GROSS_KG', 15, 2)->nullable(); // DataType 12
            $table->decimal('TOTAL_FG_NET_KG', 15, 2)->nullable(); // DataType 12
            $table->decimal('FG_UNIT_PRICE', 15, 2)->nullable(); // DataType 12
            $table->string('CURR', 100)->nullable()->collation($collation);
            $table->string('EX_RATE', 100)->nullable()->collation($collation); // DataType 27
            $table->decimal('TRAN_AMT', 15, 2)->nullable(); // DataType 12
            $table->decimal('BASE_AMT', 15, 2)->nullable(); // DataType 12
            $table->string('UID', 100)->nullable()->collation($collation); // DataType 27
            $table->string('RECEIVED_DMY', 100)->nullable()->collation($collation);
            $table->string('HMS', 100)->nullable()->collation($collation);
            $table->integer('FGDateSK')->nullable(); // DataType 3

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('NO_');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGOI_DM');
    }
}; 