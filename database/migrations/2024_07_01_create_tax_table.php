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
        Schema::create('tax', function (Blueprint $table) {
            $table->id();
            $table->string('AC_Num', 50);
            $table->string('AC_Name', 150);
            $table->string('NPWP', 50);
            $table->string('No_Faktur', 50);
            $table->string('Tgl_Faktur', 50);
            $table->string('Typ', 50);
            $table->string('INV_Num', 50);
            $table->string('Currency', 50);
            $table->float('DPP');
            $table->float('PPN');
            $table->string('Rate', 50);
            $table->string('Jenis_Barang', 150);
            $table->string('Nama_Bank', 50);
            $table->integer('DateSK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax');
    }
}; 