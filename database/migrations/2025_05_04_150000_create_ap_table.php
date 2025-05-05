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
        Schema::create('AP', function (Blueprint $table) {
            $table->string('YYYY', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('MM', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('AC_NUM', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('AC_NAME', 150)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('DMY', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('YMD', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('TYPE', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('SC_SYS', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('SC_CODE', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('REF_NUM', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('CURRENCY', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('EX_RATE')->nullable();
            $table->double('GROSS_AMOUNT')->nullable();
            $table->string('TAX', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->float('RATE_PERCENT')->nullable();
            $table->double('TAX_AMOUNT')->nullable();
            $table->double('TAX_ADJUSTMENT')->nullable();
            $table->double('AMOUNT')->nullable();
            $table->integer('TERMS')->nullable();
            $table->string('DUE_DMY', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('DUE_YMD', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('CHEQUE_NUM', 150)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('SOURCE_REF_NUM', 150)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('PURCHASE_REF_NUM', 150)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('REMARK1', 250)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('REMARK2', 250)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('REMARK3', 250)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('REMARK4', 250)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL1_AC', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL1_NAME', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('DR1')->nullable();
            $table->double('CR1')->nullable();
            $table->string('GL2_AC', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL2_NAME', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('DR2')->nullable();
            $table->double('CR2')->nullable();
            $table->string('GL3_AC', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL3_NAME', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('DR3')->nullable();
            $table->double('CR3')->nullable();
            $table->string('GL4_AC', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL4_NAME', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('DR4')->nullable();
            $table->double('CR4')->nullable();
            $table->string('GL5_AC', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL5_NAME', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('DR5')->nullable();
            $table->double('CR5')->nullable();
            $table->string('GL6_AC', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('GL6_NAME', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->double('DR6')->nullable();
            $table->double('CR6')->nullable();
            $table->string('LOG_YMD', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('LOG_HMS', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('LOG_UID', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            // Note: Standard Laravel timestamps (created_at, updated_at) and primary key (id) are not added
            // as they were not specified in the provided structure. Add them manually if needed.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AP');
    }
}; 