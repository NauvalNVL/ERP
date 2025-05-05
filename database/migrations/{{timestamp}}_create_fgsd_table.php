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
        Schema::create('FGSD', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('NO', 50)->nullable()->collation($collation);
            $table->string('AC', 50)->nullable()->collation($collation);
            $table->string('AC_NAME', 150)->nullable()->collation($collation);
            $table->string('MCS', 50)->nullable()->collation($collation);
            $table->string('MODEL', 150)->nullable()->collation($collation);
            $table->string('DESIGN', 50)->nullable()->collation($collation);
            $table->string('C', 50)->nullable()->collation($collation);
            $table->string('WO', 50)->nullable()->collation($collation);
            $table->string('SO', 50)->nullable()->collation($collation);
            $table->string('STK_IN_REF', 150)->nullable()->collation($collation);
            $table->string('RECEIVE_DATE', 50)->nullable()->collation($collation);
            $table->string('TR_TYPE', 50)->nullable()->collation($collation);
            $table->string('WHSE_LOC', 50)->nullable()->collation($collation);
            $table->string('UNIT', 50)->nullable()->collation($collation);
            $table->decimal('QTY_IN', 15, 2)->nullable(); // DataType 12
            $table->decimal('QTY_OUT', 15, 2)->nullable(); // DataType 12
            $table->string('AC_CODE', 50)->nullable()->collation($collation);
            $table->string('ORIGIN_TRAN', 50)->nullable()->collation($collation);
            $table->string('ORIGIN_REF', 50)->nullable()->collation($collation);
            $table->string('UID', 50)->nullable()->collation($collation); // DataType 27
            $table->string('SYSTEM_DATE', 50)->nullable()->collation($collation);
            $table->string('SYSTEM_TIME', 50)->nullable()->collation($collation);
            $table->integer('DateSk')->nullable(); // DataType 3

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('NO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGSD');
    }
}; 