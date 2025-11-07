<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $wo_number
 * @property string|null $so_number
 * @property string|null $customer_name
 * @property float|null $plan_qty
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $status
 * @property string|null $wo_status
 * @property string|null $so_status
 * @property string|null $acr_number
 * @property string|null $mcs_number
 * @property string|null $model
 * @property string|null $comp_number
 * @property string|null $part_number
 * @property string|null $pd
 * @property float|null $pcs_per_unit
 * @property float|null $ied_x
 * @property float|null $ied_y
 * @property float|null $completed_qty
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property string|null $description
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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
