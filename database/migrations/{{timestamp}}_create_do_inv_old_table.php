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
        Schema::create('DO_INV_OLD', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            $table->string('NO_', 100)->nullable()->collation($collation); // Note trailing underscore
            $table->string('DO_', 100)->nullable()->collation($collation); // Note trailing underscore
            $table->string('DATE', 100)->nullable()->collation($collation);
            $table->string('S', 100)->nullable()->collation($collation);
            $table->string('AC_NUM', 100)->nullable()->collation($collation);
            $table->string('CUSTOMER_NAME', 100)->nullable()->collation($collation);
            $table->string('SLM', 100)->nullable()->collation($collation);
            $table->string('NO', 100)->nullable()->collation($collation);
            $table->string('SO_NUM', 100)->nullable()->collation($collation);
            $table->string('MSC_NUM', 100)->nullable()->collation($collation);
            $table->string('MC_MODEL', 100)->nullable()->collation($collation);
            $table->string('PO_NUM', 100)->nullable()->collation($collation);
            $table->string('PD', 100)->nullable()->collation($collation);
            $table->string('P', 100)->nullable()->collation($collation);
            $table->string('UOM', 100)->nullable()->collation($collation);
            $table->decimal('DO_QTY', 15, 2)->nullable(); // DataType 12
            $table->string('GR_QTY_IV_YES', 100)->nullable()->collation($collation);
            $table->string('GR_QTY_IV_NO', 100)->nullable()->collation($collation);
            $table->decimal('NET_DO_QTY', 15, 2)->nullable(); // DataType 12
            $table->bigInteger('DO_UPRICE')->nullable(); // DataType 8
            $table->string('CUR', 100)->nullable()->collation($collation);
            $table->bigInteger('SO_EX_RATE')->nullable(); // DataType 8
            $table->bigInteger('DO_AMT')->nullable(); // DataType 8
            $table->bigInteger('DO_LOCAL_AMT')->nullable(); // DataType 8
            $table->string('IV_NUM', 100)->nullable()->collation($collation);
            $table->string('IV_DATE', 100)->nullable()->collation($collation);
            $table->decimal('IV_QTY', 15, 2)->nullable(); // DataType 12
            $table->bigInteger('IV_UPRICE')->nullable(); // DataType 8
            $table->string('IV_CURR', 100)->nullable()->collation($collation);
            $table->bigInteger('IV_EX_RATE')->nullable(); // DataType 8
            $table->bigInteger('IV_AMT')->nullable(); // DataType 8
            $table->bigInteger('IV_LOCAL_AMT')->nullable(); // DataType 8
            $table->string('STS', 100)->nullable()->collation($collation);
            $table->string('TY', 100)->nullable()->collation($collation);
            $table->string('PO_DATE', 100)->nullable()->collation($collation);
            $table->string('VHC', 100)->nullable()->collation($collation);

            // Tambahkan primary key atau index jika diperlukan
            // $table->primary(['NO_', 'DO_']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DO_INV_OLD');
    }
}; 