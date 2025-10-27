<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FGStockIn extends Model
{
    protected $table = 'FGStockIn';
    
    protected $fillable = [
        'NO',
        'AC',
        'AC_NAME',
        'MCS',
        'MODEL',
        'DESIGN',
        'C',
        'WO',
        'SO',
        'STOCKIN_REF',
        'REC_DATE',
        'TR_TYPE',
        'WHS_LOC',
        'UNIT',
        'QTY_IN',
        'QTY_OUT',
        'AC_CODE',
        'ORIGIN_TRAN',
        'ORIGIN_REF',
        'UID',
        'SYSTEM_DATE',
        'SYSTEM_TIME',
    ];
    
    public $timestamps = false;
}