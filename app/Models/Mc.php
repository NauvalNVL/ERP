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
     * ALL columns from MC table migration are now fillable.
     */
    protected $fillable = [
        // Basic Info
        'AC_NUM', 'AC_NAME', 'STS', 'COMP', 'P_DESIGN', 'MCS_Num', 'MODEL', 'FLUTE',
        
        // SO/WO Pack Quantities
        'SO_PQ1', 'SO_PQ2', 'SO_PQ3', 'SO_PQ4', 'SO_PQ5',
        'WO_PQ1', 'WO_PQ2', 'WO_PQ3', 'WO_PQ4', 'WO_PQ5',
        
        // Dimensions (Internal & External)
        'INT_LENGTH', 'INT_WIDTH', 'INT_HEIGHT',
        'EXT_LENGTH', 'EXT_WIDTH', 'EXT_HEIGHT',
        
        // Sheet & Cut Metrics
        'SHEET_LENGTH', 'SHEET_WIDTH', 'PAPER_SIZE',
        'CORR_OUT', 'SLIT_OUT', 'DIE_OUT', 'JOIN_',
        'S_TOOL', 'NEST_SLOT', 'CREASE',
        
        // Coating & Finishing
        'COAT', 'TAPE',
        
        // Scoring Length & Width (SL1-SL8, SW1-SW8)
        'SL1', 'SL2', 'SL3', 'SL4', 'SL5', 'SL6', 'SL7', 'SL8', 'TOTAL_SL',
        'SW1', 'SW2', 'SW3', 'SW4', 'SW5', 'SW6', 'SW7', 'SW8', 'TOTAL_SW',
        
        // Colors & Area Percentages
        'COLOR1', 'COLOR2', 'COLOR3', 'COLOR4', 'COLOR5', 'COLOR6', 'COLOR7',
        'COLOR1_AREA_PERCENT', 'COLOR2_AREA_PERCENT', 'COLOR3_AREA_PERCENT',
        'COLOR4_AREA_PERCENT', 'COLOR5_AREA_PERCENT', 'COLOR6_AREA_PERCENT', 'COLOR7_AREA_PERCENT',
        
        // Printing & Diecut
        'PRINTING_BLOCK_NO', 'DIECUT_MOULD_NO',
        'DC_SHT_L', 'DC_SHT_W', 'DC_MOULD_L', 'DC_MOULD_W',
        
        // Process Flags
        'FSH', 'SWIRE', 'GLUEING', 'WRAPPING', 'HAND_HOLE', 'ROTARY_DC', 'FB_PRINTING',
        
        // Additional Process Info
        'SWIRE_PCS', 'PEEL_OFF_PERCENT', 'PCS_PER_BLD', 'BLD_PER_PLD',
        'STRING_TYPE', 'ITEM_REMARK',
        
        // Pricing & Units
        'PCS_SET', 'UNIT', 'CURRENCY', 'UNIT_PRICE', 'PART_NO',
        
        // Weight & Area Calculations
        'MC_GROSS_M2_PER_PCS', 'MC_NET_M2_PER_PCS',
        'MC_GROSS_KG_PER_SET', 'MC_NET_KG_PER_PCS',
        'TOTAL_COLOR',
        
        // MSP (Machine Special Process) 1-12
        'MSP1_MCH', 'MSP1_UP', 'MSP1_SPECIAL_INST',
        'MSP2_MCH', 'MSP2_UP', 'MSP2_SPECIAL_INST',
        'MSP3_MCH', 'MSP3_UP', 'MSP3_SPECIAL_INST',
        'MSP4_MCH', 'MSP4_UP', 'MSP4_SPECIAL_INST',
        'MSP5_MCH', 'MSP5_UP', 'MSP5_SPECIAL_INST',
        'MSP6_MCH', 'MSP6_UP', 'MSP6_SPECIAL_INST',
        'MSP7_MCH', 'MSP7_UP', 'MSP7_SPECIAL_INST',
        'MSP8_MCH', 'MSP8_UP', 'MSP8_SPECIAL_INST',
        'MSP9_MCH', 'MSP9_UP', 'MSP9_SPECIAL_INST',
        'MSP10_MCH', 'MSP10_UP', 'MSP10_SPECIAL_INST',
        'MSP11_MCH', 'MSP11_UP', 'MSP11_SPECIAL_INST',
        'MSP12_MCH', 'MSP12_UP', 'MSP12_SPECIAL_INST',
        
        // MC Special Instructions (1-4)
        'MC_SPECIAL_INST1', 'MC_SPECIAL_INST2', 'MC_SPECIAL_INST3', 'MC_SPECIAL_INST4',
        
        // MC More Descriptions (1-5)
        'MC_MORE_DESCRIPTION_1', 'MC_MORE_DESCRIPTION_2', 'MC_MORE_DESCRIPTION_3',
        'MC_MORE_DESCRIPTION_4', 'MC_MORE_DESCRIPTION_5',
    ];

    public function fluteCps()
    {
        return $this->belongsTo(PaperFlute::class, 'FLUTE', 'Flute');
    }

    public function chemicalCoat()
    {
        return $this->belongsTo(ChemicalCoat::class, 'COAT', 'code');
    }

    public function wrappingMaterial()
    {
        return $this->belongsTo(WrappingMaterial::class, 'WRAPPING', 'code');
    }

    public function reinforcementTape()
    {
        return $this->belongsTo(ReinforcementTape::class, 'TAPE', 'code');
    }

    public function finishing()
    {
        return $this->belongsTo(Finishing::class, 'FSH', 'code');
    }

    public function glueingMaterial()
    {
        return $this->belongsTo(GlueingMaterial::class, 'GLUEING', 'code');
    }

    public function scoringTool()
    {
        return $this->belongsTo(ScoringTool::class, 'S_TOOL', 'code');
    }

    public function stitchWire()
    {
        return $this->belongsTo(StitchWire::class, 'SWIRE', 'code');
    }

    public function bundlingString()
    {
        return $this->belongsTo(BundlingString::class, 'STRING_TYPE', 'code');
    }

    public function productDesign()
    {
        return $this->belongsTo(ProductDesign::class, 'P_DESIGN', 'pd_code');
    }

    public function paperSize()
    {
        return $this->belongsTo(PaperSize::class, 'PAPER_SIZE', 'millimeter');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'AC_NUM', 'CODE');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'master_card_seq', 'MCS_Num');
    }
}


