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
        Schema::create('FG_BLC', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('ACC_NUM', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('CUSTOMER_NAME', 255)->nullable()->collation($collation); // Original: CUSTOMER NAME, DataType 28 (Unicode)
            $table->string('SLM', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MC', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('STS', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MODEL', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('COMP', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('DESIGN', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('PART', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->date('LAST_DO_DATE')->nullable(); // DataType 19
            $table->string('TYPE', 255)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->bigInteger('BEGINNING')->nullable(); // DataType 8
            $table->bigInteger('BEG_MT')->nullable(); // DataType 8
            $table->bigInteger('BEG_AMT')->nullable(); // DataType 8
            $table->bigInteger('IN')->nullable(); // DataType 8
            $table->bigInteger('IN_MT')->nullable(); // DataType 8
            $table->bigInteger('IN_AMT')->nullable(); // DataType 8
            $table->bigInteger('OUT')->nullable(); // DataType 8
            $table->bigInteger('OUT_MT')->nullable(); // DataType 8
            $table->bigInteger('OUT_AMT')->nullable(); // DataType 8
            $table->bigInteger('ENDING')->nullable(); // DataType 8
            $table->bigInteger('END_MT')->nullable(); // DataType 8
            $table->bigInteger('END_AMT')->nullable(); // DataType 8
            $table->string('YYYY', 50)->nullable()->collation($collation); // DataType 28 (Unicode)
            $table->string('MM', 50)->nullable()->collation($collation); // DataType 28 (Unicode)

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary(['ACC_NUM', 'MC', 'YYYY', 'MM']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FG_BLC');
    }
}; 