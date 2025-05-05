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
        Schema::create('FGOI', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('No', 50)->nullable()->collation($collation);
            $table->string('WH_Code', 50)->nullable()->collation($collation);
            $table->string('AC_Num', 50)->nullable()->collation($collation);
            $table->string('AC_Name', 50)->nullable()->collation($collation);
            $table->string('Sts', 50)->nullable()->collation($collation);
            $table->string('SLM', 8)->nullable()->collation($collation);
            $table->string('MCS_Num', 50)->nullable()->collation($collation);
            $table->string('Model', 50)->nullable()->collation($collation);
            $table->string('Comp', 38)->nullable()->collation($collation);
            $table->string('PD', 50)->nullable()->collation($collation);
            $table->string('Pcs_Set', 50)->nullable()->collation($collation);
            $table->string('Part_Num', 50)->nullable()->collation($collation);
            $table->string('Uk_Dalam', 50)->nullable()->collation($collation);
            $table->string('SO_Num', 25)->nullable()->collation($collation);
            $table->string('PO_Num', 100)->nullable()->collation($collation);
            $table->string('WO_Num', 25)->nullable()->collation($collation);
            $table->string('FGOI_Date', 25)->nullable()->collation($collation);
            $table->string('Tran_Type', 5)->nullable()->collation($collation);
            $table->string('GRX', 5)->nullable()->collation($collation);
            $table->string('Ref_Num', 100)->nullable()->collation($collation);
            $table->string('Alt_Ref_Num', 100)->nullable()->collation($collation);
            $table->decimal('In_', 15, 2)->nullable(); // DataType 12, Note trailing underscore
            $table->decimal('Out_', 15, 2)->nullable(); // DataType 12, Note trailing underscore
            $table->decimal('Balance', 15, 2)->nullable(); // DataType 12
            $table->integer('FGDateSK')->nullable(); // DataType 3

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FGOI');
    }
}; 