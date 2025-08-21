<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FgStockInByWo extends Model
{
    use HasFactory;
    
    protected $table = 'fg_stock_in_by_wo';
    
    protected $fillable = [
        'work_order_id',
        'wo_number',
        'entry_ref',
        'entry_qty',
        'entry_date',
        'warehouse',
        'analysis',
        'remark',
        'period',
        'created_by',
        'status'
    ];

    protected $casts = [
        'entry_qty' => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
}
