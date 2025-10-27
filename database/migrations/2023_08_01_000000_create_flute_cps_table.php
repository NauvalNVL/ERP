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

            // No as primary key (numeric 18,0 in SQL Server)
            $table->decimal('No', 18, 0)->primary();
            $table->string('Flute', 25)->collation($collation);
            $table->string('Descr', 100)->nullable()->collation($collation);
            
            // All numeric fields as float (matching SQL Server float type)
            $table->float('DB')->nullable();
            $table->float('B')->nullable();
            $table->float('_1L')->nullable();  // [1L] in database
            $table->float('A_C_E')->nullable(); // [A/C/E] in database
            $table->float('_2L')->nullable();  // [2L] in database
            $table->float('Height')->nullable();
            $table->float('Starch')->nullable();
            
            // No timestamps - matching CPS database structure
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