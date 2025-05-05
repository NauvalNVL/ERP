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
        Schema::create('FGStockIn', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->bigInteger('NO')->nullable(); // DataType 8
            $table->string('AC', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('AC_NAME', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MCS', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MODEL', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('DESIGN', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->bigInteger('C')->nullable(); // DataType 8
            $table->string('WO', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('SO', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('STOCKIN_REF', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('REC_DATE', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('TR_TYPE', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('WHS_LOC', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('UNIT', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->bigInteger('QTY_IN')->nullable(); // DataType 8
            $table->bigInteger('QTY_OUT')->nullable(); // DataType 8
            $table->string('AC_CODE', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->bigInteger('ORIGIN_TRAN')->nullable(); // DataType 8
            $table->bigInteger('ORIGIN_REF')->nullable(); // DataType 8
            $table->string('UID', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('SYSTEM_DATE', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('SYSTEM_TIME', 255)->nullable()->collation($collation); // DataType 28 (Unicode)

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('NO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGStockIn');
    }
}; 