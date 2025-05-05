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
        Schema::create('FGBS', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->integer('ID')->nullable(); // DataType 3
            $table->integer('UID')->nullable(); // DataType 3
            $table->string('NO_', 50)->nullable()->collation($collation); // Note trailing underscore
            $table->string('CUSTOMER_NAME', 250)->nullable()->collation($collation);
            $table->string('SLM_', 50)->nullable()->collation($collation); // Note trailing underscore
            $table->string('MC_MODEL', 250)->nullable()->collation($collation);
            $table->string('P_DESIGN', 50)->nullable()->collation($collation);
            $table->string('LAST_DO_DATE', 50)->nullable()->collation($collation);
            $table->string('TYPE_', 50)->nullable()->collation($collation); // Note trailing underscore
            $table->string('BF_', 50)->nullable()->collation($collation); // Note trailing underscore, DataType 27
            $table->string('IN_', 50)->nullable()->collation($collation); // Note trailing underscore, DataType 27
            $table->string('OUT_', 50)->nullable()->collation($collation); // Note trailing underscore, DataType 27
            $table->string('BAL_', 50)->nullable()->collation($collation); // Note trailing underscore, DataType 27
            $table->string('SO', 50)->nullable()->collation($collation);
            $table->string('FG_DATE', 50)->nullable()->collation($collation);
            $table->string('FG_TYPE', 50)->nullable()->collation($collation);
            $table->string('FG_QTY', 50)->nullable()->collation($collation); // DataType 27
            $table->string('LOC', 50)->nullable()->collation($collation);
            $table->string('LAST_SO_SCH_DATE', 50)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGBS');
    }
}; 