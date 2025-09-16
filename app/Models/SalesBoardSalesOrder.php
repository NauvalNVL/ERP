<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesBoardSalesOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_board_sales_orders';

    protected $fillable = [
        'so_number',
        'current_period',
        'update_period',
        'forward_period',
        'backward_period',
        'customer_code',
        'salesperson_code',
        'customer_p_order_number',
        'p_order_date',
        'product_design',
        'flute',
        'so_b_quality',
        'wo_b_quality',
        'board_size_length',
        'board_size_width',
        'sheet_size_length',
        'sheet_size_width',
        'paper_size',
        's_tool',
        'corr_out',
        'conv_out',
        'area_per_pcs',
        'l_meter',
        'order_quantity',
        'currency',
        'exchange_rate',
        'lot_number',
        'pcs_per_bundle',
        'sales_tax',
        'order_group',
        'order_type',
        'remark',
        'instruction_1',
        'instruction_2',
        'unit',
        'unit_price',
        'status'
    ];

    protected $casts = [
        'p_order_date' => 'date',
        'area_per_pcs' => 'decimal:4',
        'l_meter' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'sales_tax' => 'boolean',
        'order_quantity' => 'integer',
        'pcs_per_bundle' => 'integer',
        'current_period' => 'integer',
        'update_period' => 'integer',
        'forward_period' => 'integer',
        'backward_period' => 'integer'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class, 'salesperson_code', 'code');
    }

    public function productDesign()
    {
        return $this->belongsTo(ProductDesign::class, 'product_design', 'product_design_code');
    }

    public function paperFlute()
    {
        return $this->belongsTo(PaperFlute::class, 'flute', 'flute_code');
    }

    public function paperQuality()
    {
        return $this->belongsTo(PaperQuality::class, 'so_b_quality', 'quality_code');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByCustomer($query, $customerCode)
    {
        return $query->where('customer_code', $customerCode);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('current_period', $period);
    }

    // Accessors
    public function getFormattedSoNumberAttribute()
    {
        return 'SB-SO-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getBoardSizeAttribute()
    {
        return $this->board_size_length . ' L x ' . $this->board_size_width . ' W';
    }

    public function getSheetSizeAttribute()
    {
        return $this->sheet_size_length . ' L x ' . $this->sheet_size_width . ' W';
    }

    // Mutators
    public function setSoNumberAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['so_number'] = 'SB-SO-' . str_pad($this->id ?? 0, 6, '0', STR_PAD_LEFT);
        } else {
            $this->attributes['so_number'] = $value;
        }
    }
}
