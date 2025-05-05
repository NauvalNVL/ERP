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
        Schema::create('COPKTS', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('GOL', 2)->nullable()->collation($collation);
            $table->decimal('KRAFT', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('KRAFT225', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('KRAFT275', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('BKRAFT', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('MEDIUM', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('WKRAFT', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->date('TGL1')->nullable(); // DataType 19
            $table->string('PERIODE', 20)->nullable()->collation($collation);
            $table->decimal('PKKL', 15, 2)->nullable(); // DataType 12, assumed precision 15, scale 2
            $table->decimal('PKKL225', 15, 2)->nullable(); // DataType 12
            $table->decimal('PKKL275', 15, 2)->nullable(); // DataType 12
            $table->decimal('PKML', 15, 2)->nullable(); // DataType 12
            $table->decimal('PKWL', 15, 2)->nullable(); // DataType 12
            $table->decimal('PKBK', 15, 2)->nullable(); // DataType 12
            $table->decimal('PAKL', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PAKL225', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PAKL275', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PAML', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PAWL', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PABK', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('POKL', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('POKL225', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('POKL275', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('POML', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('POWL', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('POBK', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('DateSK', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PKKL_Ori', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PKKL225_Ori', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PKKL275_Ori', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PKML_Ori', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PKWL_Ori', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('PKBK_Ori', 15, 0)->nullable(); // DataType 11, Length 15,0
            $table->decimal('DPLX', 23, 0)->nullable(); // DataType 11, Length 23,0
            $table->decimal('DPLX_Ori', 23, 0)->nullable(); // DataType 11, Length 23,0

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary('PERIODE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('COPKTS');
    }
}; 