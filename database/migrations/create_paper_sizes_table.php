<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table structure matches CPS Enterprise 2020
     * Columns: NO, MILLIMETER, INCHES
     */
    public function up()
    {
        Schema::create('paper_sizes', function (Blueprint $table) {
            $table->id(); // NO column (auto increment)
            $table->decimal('millimeter', 10, 2)->unique(); // MILLIMETER column
            $table->decimal('inches', 10, 2); // INCHES column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('paper_sizes');
    }
};