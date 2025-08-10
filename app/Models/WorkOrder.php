<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'wo_number',
        'so_number',
        'customer_name',
        'plan_qty',
        'due_date',
        'status',
        'wo_status',
        'so_status',
        'acr_number',
        'mcs_number',
        'model',
        'comp_number',
        'part_number',
        'pd',
        'pcs_per_unit',
        'ied_x',
        'ied_y',
        'completed_qty',
        'start_date',
        'description',
        'created_by'
    ];

    protected $casts = [
        'plan_qty' => 'decimal:2',
        'completed_qty' => 'decimal:2',
        'due_date' => 'date',
        'start_date' => 'date',
    ];

    public function fgStockIns()
    {
        return $this->hasMany(FgStockInByWo::class, 'work_order_id');
    }

    public function getTotalReceivedAttribute()
    {
        return $this->fgStockIns()->sum('entry_qty');
    }

    public function getRemainingQtyAttribute()
    {
        return $this->plan_qty - $this->getTotalReceivedAttribute();
    }
}
