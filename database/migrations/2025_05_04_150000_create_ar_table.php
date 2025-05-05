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
        // Membuat tabel 'AR' (prefix 'dbo.' akan ditangani oleh koneksi database)
        Schema::create('AR', function (Blueprint $table) {
            $table->id(); // Menambahkan primary key standar 'id'
            $table->string('YYYY', 100)->nullable();
            $table->string('MM', 50)->nullable();
            $table->string('AC_NUM', 100)->nullable();
            $table->string('AC_NAME', 250)->nullable();
            $table->string('DMY', 50)->nullable();
            $table->string('YMD', 50)->nullable();
            $table->string('TYPE', 50)->nullable();
            $table->string('SC_SYS', 50)->nullable();
            $table->string('SC_CODE', 50)->nullable();
            $table->string('REF_NUM', 100)->nullable();
            $table->string('CURRENCY', 50)->nullable();
            $table->float('EX_RATE')->nullable();
            $table->float('GROSS_AMT')->nullable();
            $table->string('TAX', 50)->nullable();
            $table->string('RATE', 50)->nullable();
            $table->float('TAX_AMT')->nullable();
            $table->float('TAX_ADJUSTMENT')->nullable();
            $table->float('NET_AMT')->nullable();
            $table->string('SLM', 50)->nullable();
            $table->string('TERMS', 50)->nullable();
            $table->string('DUE_DMY', 100)->nullable();
            $table->string('DUE_YMD', 100)->nullable();
            $table->string('CHEQUE', 150)->nullable();
            $table->string('SOURCE_REF', 150)->nullable();
            $table->string('CUSTOMER_REF', 150)->nullable();
            $table->string('REMARK1', 250)->nullable();
            $table->string('REMARK2', 250)->nullable();
            $table->string('REMARK3', 250)->nullable();
            $table->string('REMARK4', 250)->nullable();
            $table->string('GL1_AC', 50)->nullable();
            $table->string('GL1_NAME', 50)->nullable();
            $table->float('DR1')->nullable();
            $table->float('CR1')->nullable();
            $table->string('GL2_AC', 50)->nullable();
            $table->string('GL2_NAME', 50)->nullable();
            $table->float('DR2')->nullable();
            $table->float('CR2')->nullable();
            $table->string('GL3_AC', 50)->nullable();
            $table->string('GL3_NAME', 50)->nullable();
            $table->float('DR3')->nullable();
            $table->float('CR3')->nullable();
            $table->string('GL4_AC', 50)->nullable();
            $table->string('GL4_NAME', 50)->nullable();
            $table->float('DR4')->nullable();
            $table->float('CR4')->nullable();
            $table->string('GL5_AC', 50)->nullable();
            $table->string('GL5_NAME', 50)->nullable();
            $table->float('DR5')->nullable();
            $table->float('CR5')->nullable();
            $table->string('GL6_AC', 50)->nullable();
            $table->string('GL6_NAME', 50)->nullable();
            $table->float('DR6')->nullable();
            $table->float('CR6')->nullable();
            $table->string('LOG_YMD', 50)->nullable();
            $table->string('LOG_HMS', 50)->nullable();
            $table->string('LOG_UID', 50)->nullable();
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AR');
    }
}; 