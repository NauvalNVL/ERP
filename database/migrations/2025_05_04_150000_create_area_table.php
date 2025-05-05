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
        // Membuat tabel AREA sesuai struktur yang diberikan
        Schema::create('AREA', function (Blueprint $table) {
            $table->integer('no')->nullable();
            $table->string('code', 50)->nullable()->charset('iso_1')->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('country', 50)->nullable()->charset('iso_1')->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('state', 50)->nullable()->charset('iso_1')->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('town', 50)->nullable()->charset('iso_1')->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('town_section', 50)->nullable()->charset('iso_1')->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('area', 50)->nullable()->charset('iso_1')->collation('SQL_Latin1_General_CP1_CI_AS');
            // Tidak ada primary key atau timestamps yang didefinisikan berdasarkan input
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AREA');
    }
}; 