<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWarehouseRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'default_warehouse_normal',
        'default_warehouse_excess',
        'default_warehouse_transit',
        'delivery_order_format',
        'bar_code_sticker',
        'bundle_format',
        'destination_change',
        'multi_line_quantity',
        'product_group_tie_up',
        'unapplied_fg_goods',
        'amend_do_un_qty',
        'closed_sales_order_delivery',
        'completed_sales_order_delivery',
        'outstanding_partial_delivery',
        'allow_qty',
        'allow_type',
        'less_from_invoice',
        'default_copies'
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'customer_code';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'destination_change' => 'boolean',
        'multi_line_quantity' => 'boolean',
        'product_group_tie_up' => 'boolean',
        'unapplied_fg_goods' => 'boolean',
        'amend_do_un_qty' => 'boolean',
        'allow_qty' => 'integer',
        'default_copies' => 'integer',
    ];

    /**
     * Get the customer associated with the warehouse requirement.
     */
    public function customer()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }

    /**
     * Get the delivery order format associated with the warehouse requirement.
     */
    public function deliveryOrderFormat()
    {
        return $this->belongsTo(DeliveryOrderFormat::class, 'delivery_order_format', 'code');
    }
} 