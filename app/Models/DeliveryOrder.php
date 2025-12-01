<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 'DO';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'PCS_PER_SET' => 'decimal:2',
        'INT_L' => 'decimal:2',
        'INT_W' => 'decimal:2',
        'INT_H' => 'decimal:2',
        'EXT_L' => 'decimal:2',
        'EXT_W' => 'decimal:2',
        'EXT_H' => 'decimal:2',
        'DO_Qty' => 'decimal:2',
        'DO_M3' => 'decimal:2',
        'SO_Unit_Price' => 'decimal:2',
        'Ex_Rate' => 'decimal:2',
        'DO_Tran_Amt' => 'decimal:2',
        'DO_Base_Amt' => 'decimal:2',
        'MC_Gross_M2_Per_Pcs' => 'decimal:2',
        'MC_Net_M2_Per_Pcs' => 'decimal:2',
        'Total_DO_Gross_M2' => 'decimal:2',
        'Total_DO_Net_M2' => 'decimal:2',
        'MC_Gross_Kg_Per_Pcs' => 'decimal:2',
        'Total_DO_Gross_KG' => 'decimal:2',
        'Total_DO_Net_KG' => 'decimal:2',
        'DODateSK' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'AC_Num', 'CODE');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'DO_VHC_Num', 'VEHICLE_NO');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'SO_Num', 'so_number');
    }

    public function mc()
    {
        return $this->belongsTo(Mc::class, 'MCS_Num', 'MCS_Num');
    }
}
