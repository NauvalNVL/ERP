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
		Schema::create('MC', function (Blueprint $table) {
			// Order per user specification
			$table->string('AC_NUM', 50)->nullable();
			$table->string('AC_NAME', 250)->nullable();
			$table->string('STS', 50)->nullable();
			$table->string('COMP', 50)->nullable();
			$table->string('P_DESIGN', 50)->nullable();
			$table->string('MCS_Num', 50)->nullable();
			$table->string('MODEL', 250)->nullable();
			$table->string('FLUTE', 50)->nullable();
			$table->string('SO_PQ1', 50)->nullable();
			$table->string('SO_PQ2', 50)->nullable();
			$table->string('SO_PQ3', 50)->nullable();
			$table->string('SO_PQ4', 50)->nullable();
			$table->string('SO_PQ5', 50)->nullable();
			$table->string('WO_PQ1', 50)->nullable();
			$table->string('WO_PQ2', 50)->nullable();
			$table->string('WO_PQ3', 50)->nullable();
			$table->string('WO_PQ4', 50)->nullable();
			$table->string('WO_PQ5', 50)->nullable();
			$table->decimal('INT_LENGTH', 18, 0)->nullable();
			$table->decimal('INT_WIDTH', 18, 0)->nullable();
			$table->decimal('INT_HEIGHT', 18, 0)->nullable();
			$table->decimal('EXT_LENGTH', 18, 0)->nullable();
			$table->decimal('EXT_WIDTH', 18, 0)->nullable();
			$table->decimal('EXT_HEIGHT', 18, 0)->nullable();
			$table->decimal('SHEET_LENGTH', 18, 0)->nullable();
			$table->decimal('SHEET_WIDTH', 18, 0)->nullable();
			$table->decimal('PAPER_SIZE', 18, 0)->nullable();
			$table->decimal('CORR_OUT', 18, 0)->nullable();
			$table->decimal('SLIT_OUT', 18, 0)->nullable();
			$table->decimal('DIE_OUT', 18, 0)->nullable();
			$table->decimal('JOIN_', 18, 0)->nullable();
			$table->string('S_TOOL', 50)->nullable();
			$table->decimal('NEST_SLOT', 18, 0)->nullable();
			$table->decimal('CREASE', 18, 0)->nullable();
			$table->string('COAT', 50)->nullable();
			$table->string('TAPE', 50)->nullable();
			$table->decimal('SL1', 18, 0)->nullable();
			$table->decimal('SL2', 18, 0)->nullable();
			$table->decimal('SL3', 18, 0)->nullable();
			$table->decimal('SL4', 18, 0)->nullable();
			$table->decimal('SL5', 18, 0)->nullable();
			$table->decimal('SL6', 18, 0)->nullable();
			$table->decimal('SL7', 18, 0)->nullable();
			$table->decimal('SL8', 18, 0)->nullable();
			$table->decimal('TOTAL_SL', 18, 0)->nullable();
			$table->decimal('SW1', 18, 0)->nullable();
			$table->decimal('SW2', 18, 0)->nullable();
			$table->decimal('SW3', 18, 0)->nullable();
			$table->decimal('SW4', 18, 0)->nullable();
			$table->decimal('SW5', 18, 0)->nullable();
			$table->decimal('SW6', 18, 0)->nullable();
			$table->decimal('SW7', 18, 0)->nullable();
			$table->decimal('SW8', 18, 0)->nullable();
			$table->decimal('TOTAL_SW', 18, 0)->nullable();
			$table->string('COLOR1', 50)->nullable();
			$table->string('COLOR2', 50)->nullable();
			$table->string('COLOR3', 50)->nullable();
			$table->string('COLOR4', 50)->nullable();
			$table->string('COLOR5', 50)->nullable();
			$table->string('COLOR6', 50)->nullable();
			$table->string('COLOR7', 50)->nullable();
			$table->decimal('COLOR1_AREA_PERCENT', 18, 2)->nullable();
			$table->decimal('COLOR2_AREA_PERCENT', 18, 2)->nullable();
			$table->decimal('COLOR3_AREA_PERCENT', 18, 2)->nullable();
			$table->decimal('COLOR4_AREA_PERCENT', 18, 2)->nullable();
			$table->decimal('COLOR5_AREA_PERCENT', 18, 2)->nullable();
			$table->decimal('COLOR6_AREA_PERCENT', 18, 2)->nullable();
			$table->decimal('COLOR7_AREA_PERCENT', 18, 2)->nullable();
			$table->string('PRINTING_BLOCK_NO', 50)->nullable();
			$table->string('DIECUT_MOULD_NO', 50)->nullable();
			$table->decimal('DC_SHT_L', 18, 0)->nullable();
			$table->decimal('DC_SHT_W', 18, 0)->nullable();
			$table->decimal('DC_MOULD_L', 18, 0)->nullable();
			$table->decimal('DC_MOULD_W', 18, 0)->nullable();
			$table->string('FSH', 50)->nullable();
			$table->decimal('SWIRE_PCS', 18, 0)->nullable();
			$table->string('SWIRE', 50)->nullable();
			$table->string('GLUEING', 50)->nullable();
			$table->string('WRAPPING', 50)->nullable();
			$table->string('HAND_HOLE', 50)->nullable();
			$table->string('ROTARY_DC', 50)->nullable();
			$table->string('FB_PRINTING', 50)->nullable();
			$table->decimal('PEEL_OFF_PERCENT', 18, 2)->nullable();
			$table->decimal('PCS_PER_BLD', 18, 0)->nullable();
			$table->string('STRING_TYPE', 50)->nullable();
			$table->decimal('BLD_PER_PLD', 18, 0)->nullable();
			$table->string('ITEM_REMARK', 250)->nullable();
			$table->decimal('PCS_SET', 18, 0)->nullable();
			$table->string('UNIT', 50)->nullable();
			$table->string('CURRENCY', 50)->nullable();
			$table->decimal('UNIT_PRICE', 18, 2)->nullable();
			$table->string('PART_NO', 50)->nullable();
			$table->decimal('MC_GROSS_M2_PER_PCS', 18, 6)->nullable();
			$table->decimal('MC_NET_M2_PER_PCS', 18, 6)->nullable();
			$table->decimal('MC_GROSS_KG_PER_SET', 18, 6)->nullable();
			$table->decimal('MC_NET_KG_PER_PCS', 18, 6)->nullable();
			$table->string('MSP1_MCH', 50)->nullable();
			$table->string('MSP1_UP', 50)->nullable();
			$table->string('MSP1_SPECIAL_INST', 250)->nullable();
			$table->string('MSP2_MCH', 50)->nullable();
			$table->string('MSP2_UP', 50)->nullable();
			$table->string('MSP2_SPECIAL_INST', 250)->nullable();
			$table->string('MSP3_MCH', 50)->nullable();
			$table->string('MSP3_UP', 50)->nullable();
			$table->string('MSP3_SPECIAL_INST', 250)->nullable();
			$table->string('MSP4_MCH', 50)->nullable();
			$table->string('MSP4_UP', 50)->nullable();
			$table->string('MSP4_SPECIAL_INST', 250)->nullable();
			$table->string('MSP5_MCH', 50)->nullable();
			$table->string('MSP5_UP', 50)->nullable();
			$table->string('MSP5_SPECIAL_INST', 250)->nullable();
			$table->string('MSP6_MCH', 50)->nullable();
			$table->string('MSP6_UP', 50)->nullable();
			$table->string('MSP6_SPECIAL_INST', 250)->nullable();
			$table->string('MSP7_MCH', 50)->nullable();
			$table->string('MSP7_UP', 50)->nullable();
			$table->string('MSP7_SPECIAL_INST', 250)->nullable();
			$table->string('MSP8_MCH', 50)->nullable();
			$table->string('MSP8_UP', 50)->nullable();
			$table->string('MSP8_SPECIAL_INST', 250)->nullable();
			$table->string('MSP9_MCH', 50)->nullable();
			$table->string('MSP9_UP', 50)->nullable();
			$table->string('MSP9_SPECIAL_INST', 250)->nullable();
			$table->string('MSP10_MCH', 50)->nullable();
			$table->string('MSP10_UP', 50)->nullable();
			$table->string('MSP10_SPECIAL_INST', 250)->nullable();
			$table->string('MSP11_MCH', 50)->nullable();
			$table->string('MSP11_UP', 50)->nullable();
			$table->string('MSP11_SPECIAL_INST', 250)->nullable();
			$table->string('MSP12_MCH', 50)->nullable();
			$table->string('MSP12_UP', 50)->nullable();
			$table->string('MSP12_SPECIAL_INST', 250)->nullable();
			$table->string('MC_SPECIAL_INST1', 250)->nullable();
			$table->string('MC_SPECIAL_INST2', 250)->nullable();
			$table->string('MC_SPECIAL_INST3', 250)->nullable();
			$table->string('MC_SPECIAL_INST4', 250)->nullable();
			$table->string('MC_MORE_DESCRIPTION_1', 250)->nullable();
			$table->string('MC_MORE_DESCRIPTION_2', 250)->nullable();
			$table->string('MC_MORE_DESCRIPTION_3', 250)->nullable();
			$table->string('MC_MORE_DESCRIPTION_4', 250)->nullable();
			$table->string('MC_MORE_DESCRIPTION_5', 250)->nullable();
			$table->decimal('TOTAL_COLOR', 18, 0)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('MC');
	}
};


