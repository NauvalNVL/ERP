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
        Schema::create('FGBLC', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('YYYY', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MM', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('Act', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('Customer_Name', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MCS', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('SO_Num', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->bigInteger('Beg_Balance')->nullable(); // DataType 8
            $table->bigInteger('Beg_MT')->nullable(); // DataType 8
            $table->bigInteger('Beg_Amount')->nullable(); // DataType 8
            $table->bigInteger('In_Balance')->nullable(); // DataType 8
            $table->bigInteger('In_MT')->nullable(); // DataType 8
            $table->bigInteger('In_Amount')->nullable(); // DataType 8
            $table->bigInteger('Adj_Balance')->nullable(); // DataType 8
            $table->bigInteger('Adj_MT')->nullable(); // DataType 8
            $table->bigInteger('Adj_Amount')->nullable(); // DataType 8
            $table->bigInteger('End_Balance')->nullable(); // DataType 8
            $table->bigInteger('End_MT')->nullable(); // DataType 8
            $table->bigInteger('End_Amount')->nullable(); // DataType 8
            $table->string('CO', 50)->nullable()->collation($collation); // DataType 28 (Unicode)

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary(['YYYY', 'MM', 'Act', 'MCS', 'SO_Num']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGBLC');
    }
}; 