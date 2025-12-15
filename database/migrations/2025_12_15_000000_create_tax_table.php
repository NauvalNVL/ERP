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
        Schema::create('TAX', function (Blueprint $table) {
            // Customer / Invoice identifiers
            $table->string('AC_Num', 50)->nullable();
            $table->string('AC_Name', 150)->nullable();
            $table->string('NPWP', 50)->nullable();
            $table->string('No_Faktur', 50)->nullable();
            $table->string('Tgl_Faktur', 50)->nullable();
            $table->string('Typ', 50)->nullable();
            $table->string('INV_Num', 50)->nullable();
            $table->string('Currency', 50)->nullable();

            // Amounts
            $table->decimal('DPP', 15, 2)->nullable();
            $table->decimal('PPN', 15, 2)->nullable();

            // Additional metadata
            $table->string('Rate', 50)->nullable();
            $table->string('Jenis_Barang', 150)->nullable();
            $table->string('Nama_Bank', 50)->nullable();
            $table->integer('DateSK')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TAX');
    }
};
