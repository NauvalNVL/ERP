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
        // Membuat tabel ALT_ADDRESS (Schema 'dbo' biasanya diatur dalam konfigurasi koneksi)
        Schema::create('ALT_ADDRESS', function (Blueprint $table) {
            // Berdasarkan DataType=3, AllowNull=1
            $table->integer('ID')->nullable();
            // Berdasarkan DataType=3, AllowNull=1
            $table->integer('UID')->nullable();
            // Berdasarkan DataType=27, LengthSet=50, AllowNull=1, Collation
            $table->string('field1', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            // Berdasarkan DataType=27, LengthSet=200, AllowNull=1, Collation
            $table->string('field2', 200)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            // Berdasarkan DataType=27, LengthSet=200, AllowNull=1, Collation
            $table->string('field3', 200)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            // Berdasarkan DataType=27, LengthSet=200, AllowNull=1, Collation
            $table->string('field4', 200)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            // Berdasarkan DataType=27, LengthSet=200, AllowNull=1, Collation
            $table->string('field5', 200)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');

            // Tidak ada informasi primary key atau timestamps dari struktur yang diberikan.
            // Jika 'ID' adalah primary key, gunakan: $table->id(); atau $table->integer('ID')->primary();
            // Jika perlu timestamps, tambahkan: $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ALT_ADDRESS');
    }
}; 