<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'customer_code',
        'customer_name',
        'customer_address',
        'credit_terms',
        'credit_limit',
        'salesperson_code',
        'currency',
        'exchange_rate',
        'master_card_seq',
        'master_card_model',
        'p_design',
        'uom',
        'order_mode',
        'product_code',
        'order_group',
        'order_type',
        'sales_tax',
        'lot_number',
        'customer_po_number',
        'po_date',
        'remark',
        'instruction1',
        'instruction2',
        'status',
        'created_by',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'sales_tax' => 'boolean',
        'po_date' => 'date',
    ];

    /**
     * Get the sales order details for this order.
     */
    public function details(): HasMany
    {
        return $this->hasMany(SalesOrderDetail::class, 'so_number', 'so_number');
    }

    /**
     * Get the delivery schedules for this order.
     */
    public function deliverySchedules(): HasMany
    {
        return $this->hasMany(DeliverySchedule::class, 'so_number', 'so_number');
    }

    /**
     * Get the total order quantity.
     */
    public function getTotalOrderQuantityAttribute(): float
    {
        return $this->details()->sum('order_quantity');
    }

    /**
     * Get the total order value.
     */
    public function getTotalOrderValueAttribute(): float
    {
        return $this->details()->sum('line_total');
    }

    /**
     * Get the total scheduled quantity.
     */
    public function getTotalScheduledQuantityAttribute(): float
    {
        return $this->deliverySchedules()->sum('delivery_quantity');
    }

    /**
     * Get the remaining quantity to be scheduled.
     */
    public function getRemainingQuantityAttribute(): float
    {
        return $this->total_order_quantity - $this->total_scheduled_quantity;
    }

    /**
     * Check if the order is fully scheduled.
     */
    public function isFullyScheduled(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    /**
     * Check if the order has any delivery schedules.
     */
    public function hasDeliverySchedules(): bool
    {
        return $this->deliverySchedules()->exists();
    }
}


