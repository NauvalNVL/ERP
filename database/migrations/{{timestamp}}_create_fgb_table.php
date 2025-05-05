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
        Schema::create('FGB', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('NO', 50)->nullable()->collation($collation);
            $table->string('AC_NAME', 50)->nullable()->collation($collation);
            $table->string('SALES', 50)->nullable()->collation($collation);
            $table->string('MC_MODEL', 50)->nullable()->collation($collation);
            $table->string('P_DESIGN_PARTNUM', 50)->nullable()->collation($collation);
            $table->string('LAST_DO_DATE', 50)->nullable()->collation($collation);
            $table->string('TIPE', 50)->nullable()->collation($collation);
            $table->decimal('BF', 15, 2)->nullable(); // DataType 12
            $table->decimal('IN_FG', 15, 2)->nullable(); // DataType 12
            $table->decimal('OUT_FG', 15, 2)->nullable(); // DataType 12
            $table->decimal('BAL', 15, 2)->nullable(); // DataType 12
            $table->string('SO', 50)->nullable()->collation($collation);
            $table->string('FG_DATE', 50)->nullable()->collation($collation);
            $table->string('FG_TYPE', 50)->nullable()->collation($collation);
            $table->decimal('FG_QTY', 15, 2)->nullable(); // DataType 12
            $table->string('WHS_LOC', 50)->nullable()->collation($collation);
            $table->string('LAST_SO_SCH_DATE', 50)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('NO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGB');
    }
}; 