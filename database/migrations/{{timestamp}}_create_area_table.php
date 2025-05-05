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
        // Membuat tabel AREA$. Perhatikan potensi masalah dengan '$' dalam nama tabel
        // dan '#' serta spasi dalam nama kolom, tergantung pada database dan konfigurasinya.
        Schema::create('AREA$', function (Blueprint $table) {
            $table->bigInteger('NO#')->nullable(); // DataType 8, AllowNull=1
            $table->string('CODE', 255)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // DataType 28, Length 255, AllowNull=1
            $table->string('COUNTRY', 255)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // DataType 28, Length 255, AllowNull=1
            $table->string('STATE', 255)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // DataType 28, Length 255, AllowNull=1
            $table->string('TOWN', 255)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // DataType 28, Length 255, AllowNull=1
            $table->string('TOWN SECTION', 255)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // DataType 28, Length 255, AllowNull=1 (Nama kolom dengan spasi)
            $table->string('AREA', 255)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // DataType 28, Length 255, AllowNull=1

            // Jika Anda membutuhkan primary key atau index, tambahkan di sini. Contoh:
            // $table->primary('NO#');
            // $table->index('CODE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AREA$');
    }
}; 