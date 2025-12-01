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
        // If legacy table already exists, skip creation to avoid SQL Server error
        if (!Schema::hasTable('paper_sizes')) {
            Schema::create('paper_sizes', function (Blueprint $table) {
                $table->id(); // NO column (auto increment)
                $table->decimal('millimeter', 10, 2)->unique(); // MILLIMETER column
                $table->decimal('inches', 10, 2); // INCHES column
                $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');
            });
        }

        // Relasi ke MC: MC.PAPER_SIZE menyimpan nilai millimeter dari paper_sizes
        Schema::table('MC', function (Blueprint $table) {
            $table->foreign('PAPER_SIZE')
                  ->references('millimeter')
                  ->on('paper_sizes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('MC', function (Blueprint $table) {
            $table->dropForeign(['PAPER_SIZE']);
        });
        Schema::dropIfExists('paper_sizes');
    }
};
