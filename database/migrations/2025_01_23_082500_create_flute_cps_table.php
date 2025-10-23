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

            $table->decimal('No', 18, 0)->nullable();
            $table->string('Flute', 25)->primary()->collation($collation);
            $table->string('Descr', 100)->nullable()->collation($collation);
            $table->decimal('DB', 10, 2)->nullable()->default(1.00);
            $table->decimal('B', 10, 2)->nullable()->default(1.00);
            $table->decimal('_1L', 10, 2)->nullable()->default(1.00);
            $table->decimal('A_C_E', 10, 2)->nullable()->default(1.00);
            $table->decimal('_2L', 10, 2)->nullable()->default(1.00);
            $table->decimal('Height', 10, 2)->nullable()->default(0.00);
            $table->decimal('Starch', 10, 2)->nullable()->default(0.00);
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