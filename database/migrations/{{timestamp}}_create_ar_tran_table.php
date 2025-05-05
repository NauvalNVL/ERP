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
        Schema::create('AR_TRAN', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('YYYY', 100)->nullable()->collation($collation);
            $table->string('MM', 100)->nullable()->collation($collation);
            $table->string('AC_NUM', 100)->nullable()->collation($collation);
            $table->string('AC_NAME', 150)->nullable()->collation($collation);
            $table->string('DMY', 100)->nullable()->collation($collation);
            $table->string('YMD', 100)->nullable()->collation($collation);
            $table->string('TYPE', 100)->nullable()->collation($collation);
            $table->string('SC_SYS', 100)->nullable()->collation($collation);
            $table->string('SC_CODE', 100)->nullable()->collation($collation);
            $table->string('REF', 250)->nullable()->collation($collation);
            $table->string('CURRENCY', 100)->nullable()->collation($collation);
            $table->string('EX_RATE', 100)->nullable()->collation($collation);
            $table->string('GROSS_AMT', 100)->nullable()->collation($collation);
            $table->string('TAX', 100)->nullable()->collation($collation);
            $table->string('RATE', 100)->nullable()->collation($collation);
            $table->string('TAX_AMT', 100)->nullable()->collation($collation);
            $table->string('TAX_ADJUSTMENT', 100)->nullable()->collation($collation);
            $table->string('NET_AMT', 100)->nullable()->collation($collation);
            $table->string('SLM', 100)->nullable()->collation($collation);
            $table->string('TERMS', 100)->nullable()->collation($collation);
            $table->string('DUE_DMY', 100)->nullable()->collation($collation);
            $table->string('DUE_YMD', 100)->nullable()->collation($collation);
            $table->string('CHEQUE', 150)->nullable()->collation($collation);
            $table->string('SOURCE_REF', 150)->nullable()->collation($collation);
            $table->string('CUSTOMER_REF', 150)->nullable()->collation($collation);
            $table->string('REMARK1', 250)->nullable()->collation($collation);
            $table->string('REMARK2', 250)->nullable()->collation($collation);
            $table->string('REMARK3', 250)->nullable()->collation($collation);
            $table->string('REMARK4', 250)->nullable()->collation($collation);
            $table->string('GL1_AC', 100)->nullable()->collation($collation);
            $table->string('GL1_NAME', 100)->nullable()->collation($collation);
            $table->string('DR1', 100)->nullable()->collation($collation);
            $table->string('CR1', 100)->nullable()->collation($collation);
            $table->string('GL2_AC', 100)->nullable()->collation($collation);
            $table->string('GL2_NAME', 100)->nullable()->collation($collation);
            $table->string('DR2', 100)->nullable()->collation($collation);
            $table->string('CR2', 100)->nullable()->collation($collation);
            $table->string('GL3_AC', 100)->nullable()->collation($collation);
            $table->string('GL3_NAME', 100)->nullable()->collation($collation);
            $table->string('DR3', 100)->nullable()->collation($collation);
            $table->string('CR3', 100)->nullable()->collation($collation);
            $table->string('GL4_AC', 100)->nullable()->collation($collation);
            $table->string('GL4_NAME', 100)->nullable()->collation($collation);
            $table->string('DR4', 100)->nullable()->collation($collation);
            $table->string('CR4', 100)->nullable()->collation($collation);
            $table->string('GL5_AC', 100)->nullable()->collation($collation);
            $table->string('GL5_NAME', 100)->nullable()->collation($collation);
            $table->string('DR5', 100)->nullable()->collation($collation);
            $table->string('CR5', 100)->nullable()->collation($collation);
            $table->string('GL6_AC', 100)->nullable()->collation($collation);
            $table->string('GL6_NAME', 100)->nullable()->collation($collation);
            $table->string('DR6', 100)->nullable()->collation($collation);
            $table->string('CR6', 100)->nullable()->collation($collation);
            $table->string('LOG_YMD', 100)->nullable()->collation($collation);
            $table->string('LOG_HMS', 100)->nullable()->collation($collation);
            $table->string('LOG_UID', 100)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary(['YYYY', 'MM', 'AC_NUM', 'SC_CODE']); // Contoh composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AR_TRAN');
    }
}; 