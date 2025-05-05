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
        Schema::create('FGBS_AGING', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->integer('ID')->nullable(); // DataType 3
            $table->integer('UID')->nullable(); // DataType 3
            $table->string('NO_', 50)->nullable()->collation($collation); // Note trailing underscore
            $table->string('AC_NUM', 50)->nullable()->collation($collation);
            $table->string('AC_NAME', 50)->nullable()->collation($collation);
            $table->string('MCS_NUM', 50)->nullable()->collation($collation);
            $table->string('MC_STS', 50)->nullable()->collation($collation);
            $table->string('MODEL', 50)->nullable()->collation($collation);
            $table->string('SLS_CODE', 10)->nullable()->collation($collation);
            $table->string('SLS_NAME', 50)->nullable()->collation($collation);
            $table->string('COMP', 50)->nullable()->collation($collation);
            $table->string('P_DESIGN', 50)->nullable()->collation($collation);
            $table->string('TYPE', 50)->nullable()->collation($collation);
            $table->decimal('TOTAL', 15, 2)->nullable(); // DataType 12
            $table->decimal('LESS_1', 15, 2)->nullable(); // DataType 12
            $table->decimal('MONTHS_2', 15, 2)->nullable(); // DataType 12
            $table->decimal('MONTHS_3', 15, 2)->nullable(); // DataType 12
            $table->decimal('MORE_3', 15, 2)->nullable(); // DataType 12

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGBS_AGING');
    }
}; 