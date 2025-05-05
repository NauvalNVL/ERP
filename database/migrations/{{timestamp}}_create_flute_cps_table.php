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
        Schema::create('Flute_CPS', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->decimal('No', 18, 0)->nullable(); // DataType 11 (18,0)
            $table->string('Flute', 25)->nullable()->collation($collation);
            $table->string('Descr', 100)->nullable()->collation($collation);
            $table->bigInteger('DB')->nullable(); // DataType 8
            $table->bigInteger('B')->nullable(); // DataType 8
            $table->bigInteger('_1L')->nullable(); // Original: 1L, DataType 8
            $table->bigInteger('A_C_E')->nullable(); // Original: A/C/E, DataType 8
            $table->bigInteger('_2L')->nullable(); // Original: 2L, DataType 8
            $table->bigInteger('Height')->nullable(); // DataType 8
            $table->bigInteger('Starch')->nullable(); // DataType 8

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('No');
            // $table->index('Flute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Flute_CPS');
    }
}; 