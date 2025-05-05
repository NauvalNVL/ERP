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
        Schema::create('sku_val', function (Blueprint $table) {
            $table->id();
            $table->string('NO', 50);
            $table->string('CAT', 50);
            $table->string('SKU', 50);
            $table->string('NAME', 50);
            $table->string('BASE_UNIT', 50);
            $table->string('BF_IC_QTY', 50);
            $table->string('BF_WIP_QTY', 50);
            $table->string('BF_U_COST', 50);
            $table->string('BF_VALUE', 50);
            $table->string('TM_RCN_QTY', 50);
            $table->string('TM_RCN_COST', 50);
            $table->string('TM_RTN_QTY', 50);
            $table->string('TM_RTN_COST', 50);
            $table->string('TM_DRN_COST', 50);
            $table->string('TM_CRN_COST', 50);
            $table->string('TM_NET_QTY', 50);
            $table->string('TM_NET_COST', 50);
            $table->string('TM_AVG_COST', 50);
            $table->string('TM_MN_AVG_U_COST', 50);
            $table->string('TM_IS_QTY', 50);
            $table->string('TM_IS_COST', 50);
            $table->string('TM_MI_QTY', 50);
            $table->string('TM_MI_COST', 50);
            $table->string('TM_MO_QTY', 50);
            $table->string('TM_MO_COST', 50);
            $table->string('AUTO_DIFF', 50);
            $table->string('CLS_IC_QTY', 50);
            $table->string('CLS_WIP_QTY', 50);
            $table->string('CLS_MNU_COST', 50);
            $table->string('CLS_VALUE', 50);
            $table->string('ERROR', 50);
            $table->integer('DateSk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_val');
    }
}; 