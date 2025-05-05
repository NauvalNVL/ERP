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
        Schema::create('AP_PAYABLE', function (Blueprint $table) {
            // Kolom string berdasarkan DataType=27, LengthSet=100/150, AllowNull=1
            $table->string('YYYY', 100)->nullable();
            $table->string('MM', 100)->nullable();
            $table->string('AC_NUM', 100)->nullable();
            $table->string('AC_NAME', 100)->nullable();
            $table->string('DMY', 100)->nullable();
            $table->string('YMD', 100)->nullable();
            $table->string('TYPE', 100)->nullable();
            $table->string('SOURCE_SYS', 100)->nullable();
            $table->string('SOURCE_CODE', 100)->nullable();
            $table->string('REF_NUM', 100)->nullable();
            $table->string('CURRENCY', 100)->nullable();
            $table->string('TAX', 100)->nullable();
            $table->string('TERMS', 100)->nullable();
            $table->string('DUE_DMY', 100)->nullable();
            $table->string('DUE_YMD', 100)->nullable();
            $table->string('CHEQUE', 100)->nullable();
            $table->string('SOURCE_REF', 150)->nullable();
            $table->string('PURCHASE_REF', 150)->nullable();
            $table->string('REMARK1', 150)->nullable();
            $table->string('REMARK2', 150)->nullable();
            $table->string('REMARK3', 150)->nullable();
            $table->string('REMARK4', 150)->nullable();
            $table->string('RC_NUM', 100)->nullable();
            $table->string('LOG_YMD', 100)->nullable();
            $table->string('LOG_HMS', 100)->nullable();
            $table->string('LOG_UID', 100)->nullable();

            // Kolom decimal berdasarkan DataType=11, LengthSet=38,0, AllowNull=1
            $table->decimal('LOCAL_AMOUNT', 38, 0)->nullable();
            $table->decimal('LOCAL_OFFSET', 38, 0)->nullable();
            $table->decimal('LOCAL_BALANCE', 38, 0)->nullable();
            $table->decimal('FOREIGN_AMOUNT', 38, 0)->nullable();
            $table->decimal('FOREIGN_OFFSET', 38, 0)->nullable();
            $table->decimal('FOREIGN_BALANCE', 38, 0)->nullable();
            $table->decimal('EX_RATE', 38, 0)->nullable();
            $table->decimal('RATE', 38, 0)->nullable();

            // Opsional: Tambahkan primary key jika diperlukan
            // $table->id(); // Atau definisikan primary key custom jika ada

            // Opsional: Tambahkan timestamps jika diperlukan
            // $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AP_PAYABLE');
    }
}; 