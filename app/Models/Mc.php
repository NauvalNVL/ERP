<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mc extends Model
{
    /**
     * The table associated with the model.
     * Using legacy uppercase table name as defined by migration.
     */
    protected $table = 'MC';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     * The legacy table has no primary key; disable incrementing.
     */
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     * Keep a concise subset; we will usually use updateOrInsert via DB.
     */
    protected $fillable = [
        'AC_NUM',
        'AC_NAME',
        'STS',
        'COMP',
        'P_DESIGN',
        'MCS_Num',
        'MODEL',
        'FLUTE',
        'SO_PQ1', 'SO_PQ2', 'SO_PQ3', 'SO_PQ4', 'SO_PQ5',
        'WO_PQ1', 'WO_PQ2', 'WO_PQ3', 'WO_PQ4', 'WO_PQ5',
        'S_TOOL', 'COAT', 'TAPE',
        'COLOR1', 'COLOR2', 'COLOR3', 'COLOR4', 'COLOR5', 'COLOR6', 'COLOR7',
        'PRINTING_BLOCK_NO', 'DIECUT_MOULD_NO', 'FSH', 'SWIRE', 'GLUEING', 'WRAPPING', 'HAND_HOLE', 'ROTARY_DC', 'FB_PRINTING',
        'STRING_TYPE', 'ITEM_REMARK', 'UNIT', 'CURRENCY', 'PART_NO',
        'INT_LENGTH', 'INT_WIDTH', 'INT_HEIGHT',
        'EXT_LENGTH', 'EXT_WIDTH', 'EXT_HEIGHT',
        'SHEET_LENGTH', 'SHEET_WIDTH', 'PAPER_SIZE',
        'CORR_OUT', 'SLIT_OUT', 'DIE_OUT', 'JOIN_', 'NEST_SLOT', 'CREASE',
        'SL1','SL2','SL3','SL4','SL5','SL6','SL7','SL8','TOTAL_SL',
        'SW1','SW2','SW3','SW4','SW5','SW6','SW7','SW8','TOTAL_SW',
        'COLOR1_AREA_PERCENT','COLOR2_AREA_PERCENT','COLOR3_AREA_PERCENT','COLOR4_AREA_PERCENT','COLOR5_AREA_PERCENT','COLOR6_AREA_PERCENT','COLOR7_AREA_PERCENT',
        'DC_SHT_L','DC_SHT_W','DC_MOULD_L','DC_MOULD_W',
        'SWIRE_PCS','PEEL_OFF_PERCENT','PCS_PER_BLD','STRING_TYPE_VALUE','BLD_PER_PLD','PCS_SET','UNIT_PRICE',
        'MC_GROSS_M2_PER_PCS','MC_NET_M2_PER_PCS','MC_GROSS_KG_PER_SET','MC_NET_KG_PER_PCS','TOTAL_COLOR',
    ];
}


