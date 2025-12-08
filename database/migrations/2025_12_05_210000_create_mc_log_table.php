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
        Schema::create('MC_LOG', function (Blueprint $table) {
            // Kolom mengikuti struktur tabel legacy MC_LOG
            $table->string('NO', 25);           // Nomor log, mengikuti kolom NO (varchar(25))
            $table->string('DATE', 10)->nullable();   // Tanggal dalam format teks (mis. YYYY-MM-DD)
            $table->string('TIME', 10)->nullable();   // Waktu dalam format teks (mis. HH:MM:SS)
            $table->string('ACTION', 25)->nullable(); // Jenis aksi (UPDATE, OBSOLETE, REACTIVE, MAINTENANCE, dll.)
            $table->string('UID', 15)->nullable();    // User ID utama
            $table->string('UID2', 15)->nullable();   // User ID kedua (jika ada approval / second user)
            $table->string('AC_NUM', 25)->nullable(); // Nomor Account (AC#)
            $table->string('AC_NAME', 50)->nullable();// Nama Account
            $table->string('MCS_NUM', 50)->nullable();// Nomor Master Card (MCS#)
            $table->string('MODEL', 30)->nullable();  // Model / MC Model
            $table->integer('DateSK')->nullable();    // Date surrogate key (mis. YYYYMMDD)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MC_LOG');
    }
};
