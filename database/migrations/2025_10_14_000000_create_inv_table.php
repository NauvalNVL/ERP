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
        Schema::create('INV', function (Blueprint $table) {
            // Strings (lengths per provided spec), all nullable unless specified otherwise
            $table->string('YYYY', 50)->nullable();
            $table->string('MM', 50)->nullable();
            $table->string('IV_NUM', 50)->nullable();
            $table->string('IV_STS', 50)->nullable();
            $table->string('IV_DMY', 50)->nullable();

            // Integers
            $table->integer('AR_TERM')->nullable();

            // More strings
            $table->string('IV_SECOND_REF', 50)->nullable();
            $table->string('AC_NUM', 50)->nullable();
            $table->string('AC_NAME', 250)->nullable();
            $table->string('ITEM', 50)->nullable();
            $table->string('MCS_NUM', 50)->nullable();
            $table->string('MODEL', 250)->nullable();
            $table->string('PRODUCT', 50)->nullable();
            $table->string('COMP', 50)->nullable();
            $table->string('P_DESIGN', 50)->nullable();

            // Numeric (use decimal for precision)
            $table->decimal('PCS_PER_SET', 18, 4)->nullable();

            // More strings
            $table->string('UNIT', 50)->nullable();
            $table->string('PART', 50)->nullable();

            // Internal and external dimensions
            $table->decimal('INT_L', 18, 4)->nullable();
            $table->decimal('INT_W', 18, 4)->nullable();
            $table->decimal('INT_H', 18, 4)->nullable();
            $table->decimal('EXT_L', 18, 4)->nullable();
            $table->decimal('EXT_W', 18, 4)->nullable();
            $table->decimal('EXT_H', 18, 4)->nullable();

            // More strings
            $table->string('FLUTE', 50)->nullable();
            $table->string('SLM', 50)->nullable();
            $table->string('IND', 50)->nullable();
            $table->string('AREA', 50)->nullable();
            $table->string('GROUP_', 50)->nullable();
            $table->string('SO_NUM', 50)->nullable();
            $table->string('SO_TYPE', 50)->nullable();
            $table->string('SO_DMY', 50)->nullable();
            $table->string('PO_NUM', 250)->nullable();
            $table->string('PO_DMY', 50)->nullable();
            $table->string('LOT_NUM', 50)->nullable();
            $table->string('SO_PQ1', 50)->nullable();
            $table->string('SO_PQ2', 50)->nullable();
            $table->string('SO_PQ3', 50)->nullable();
            $table->string('SO_PQ4', 50)->nullable();
            $table->string('SO_PQ5', 50)->nullable();

            // Quantities and pricing
            $table->decimal('IV_QTY', 18, 4)->nullable();
            $table->decimal('IV_UNIT_PRICE', 18, 4)->nullable();
            $table->string('CURR', 50)->nullable();
            $table->decimal('EX_RATE', 18, 6)->nullable();

            // Amounts
            $table->decimal('IV_TRAN_AMT', 18, 2)->nullable();
            $table->decimal('IV_BASE_AMT', 18, 2)->nullable();

            // Measures
            $table->decimal('MC_GROSS_M2_PER__PCS', 18, 4)->nullable();
            $table->decimal('MC_NET_M2_PER_PCS', 18, 4)->nullable();
            $table->decimal('TOTAL_IV_GROSS_M2', 18, 4)->nullable();
            $table->decimal('TOTAL_IV_NET_M2', 18, 4)->nullable();
            $table->decimal('MC_GROSS_KG_PER_PCS', 18, 4)->nullable();
            $table->decimal('MC_NET_KG_PER_PCS', 18, 4)->nullable();
            $table->decimal('TOTAL_IV_GROSS_KG', 18, 4)->nullable();
            $table->decimal('TOTAL_IV_NET_KG', 18, 4)->nullable();

            // Taxes and remarks
            $table->string('IV_TAX_CODE', 50)->nullable();
            $table->decimal('IV_TAX_PERCENT', 5, 2)->nullable();
            $table->string('IV_REMARK', 50)->nullable();
            $table->string('CANCELLED_REASON_1', 100)->nullable();
            $table->string('cANCELLED_REASON_2', 100)->nullable();

            // Audit fields - User IDs (references usercps.userID)
            $table->string('NW_UID', 50)->nullable()->comment('New/Created by User ID');
            $table->string('NW_DATE', 50)->nullable();
            $table->string('NW_TIME', 50)->nullable();
            $table->string('AM_UID', 50)->nullable()->comment('Amended by User ID');
            $table->string('AM_DATE', 50)->nullable();
            $table->string('AM_TIME', 50)->nullable();
            $table->string('CX_UID', 50)->nullable()->comment('Cancelled by User ID');
            $table->string('CX_DATE', 50)->nullable();
            $table->string('CX_TIME', 50)->nullable();
            $table->string('PT_UID', 50)->nullable()->comment('Printed by User ID');
            $table->string('PT_DATE', 50)->nullable();
            $table->string('PT_TIME', 50)->nullable();

            // Note: Foreign keys not added because usercps.userID is not a primary key
            // Relationships are handled via Eloquent (Invoice->createdByUser(), etc.)

            // Date surrogate keys
            $table->integer('IVDateSK')->nullable();
            $table->integer('SODateSK')->nullable();
            $table->integer('PODateSK')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('INV');
    }
};


