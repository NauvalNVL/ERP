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
        Schema::create('DO_SCH', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('No', 50)->nullable()->collation($collation);
            $table->string('CUST_CODE', 50)->nullable()->collation($collation);
            $table->string('MC_NUM', 50)->nullable()->collation($collation);
            $table->string('Model', 150)->nullable()->collation($collation);
            $table->string('PO_NUM', 50)->nullable()->collation($collation);
            $table->string('Loc', 50)->nullable()->collation($collation);
            $table->string('DueDate', 50)->nullable()->collation($collation);
            $table->string('Time', 50)->nullable()->collation($collation);
            $table->string('SO_NUM', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS'); // Collation specified differently, use the provided one
            $table->string('Comp', 50)->nullable()->collation($collation);
            $table->string('P_DESIGN', 50)->nullable()->collation($collation);
            $table->string('EXT_DIMENSION', 50)->nullable()->collation($collation);
            $table->string('PART_NUM', 50)->nullable()->collation($collation);
            $table->decimal('Pcs', 15, 2)->nullable(); // DataType 12
            $table->string('Unt', 50)->nullable()->collation($collation);
            $table->decimal('OrderQty', 15, 2)->nullable(); // DataType 12
            $table->decimal('NetDO', 15, 2)->nullable(); // DataType 12
            $table->decimal('Balance', 15, 2)->nullable(); // DataType 12
            $table->string('Due', 50)->nullable()->collation($collation);
            $table->string('Due_Remark', 50)->nullable()->collation($collation);
            $table->decimal('DueQty', 15, 2)->nullable(); // DataType 12
            $table->decimal('ToDO', 15, 2)->nullable(); // DataType 12
            $table->string('Action', 50)->nullable()->collation($collation);
            $table->decimal('FGReady', 15, 2)->nullable(); // DataType 12
            $table->integer('DateSKDue')->nullable(); // DataType 3

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DO_SCH');
    }
}; 