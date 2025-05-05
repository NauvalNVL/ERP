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
        Schema::create('FGSI', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('NO', 200)->nullable()->collation($collation);
            $table->string('AC', 1000)->nullable()->collation($collation);
            $table->string('AC_NAME', 1000)->nullable()->collation($collation);
            $table->string('MCS', 200)->nullable()->collation($collation);
            $table->string('MODEL', 200)->nullable()->collation($collation);
            $table->string('DESIGN', 200)->nullable()->collation($collation);
            $table->string('C', 200)->nullable()->collation($collation);
            $table->string('WO', 200)->nullable()->collation($collation);
            $table->string('SO', 200)->nullable()->collation($collation);
            $table->string('STK_IN_REF', 200)->nullable()->collation($collation);
            $table->string('RECEIVE_DATE', 200)->nullable()->collation($collation);
            $table->string('TR_TYPE', 200)->nullable()->collation($collation);
            $table->string('WHSE_LOC', 200)->nullable()->collation($collation);
            $table->string('UNIT', 200)->nullable()->collation($collation);
            $table->string('QTY_IN', 200)->nullable()->collation($collation); // DataType 27
            $table->string('QTY_OUT', 200)->nullable()->collation($collation); // DataType 27
            $table->string('AC_CODE', 200)->nullable()->collation($collation);
            $table->string('ORIGIN_TRAN', 200)->nullable()->collation($collation);
            $table->string('ORIGIN_REF', 200)->nullable()->collation($collation);
            $table->string('UID', 200)->nullable()->collation($collation); // DataType 27
            $table->string('SYSTEM_DATE', 200)->nullable()->collation($collation);
            $table->string('SYSTEM_TIME', 200)->nullable()->collation($collation);
            $table->integer('RcvDateSk')->nullable(); // DataType 3

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('NO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGSI');
    }
}; 