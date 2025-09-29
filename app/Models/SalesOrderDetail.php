<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'line_number',
        'item_code',
        'item_description',
        'order_quantity',
        'unit_price',
        'line_total',
        'uom',
        'remark',
    ];

    protected $casts = [
        'order_quantity' => 'decimal:2',
        'unit_price' => 'decimal:4',
        'line_total' => 'decimal:2',
    ];

    /**
     * Get the sales order that owns this detail.
     */
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'so_number', 'so_number');
    }

    /**
     * Get the delivery schedules for this line item.
     */
    public function deliverySchedules(): HasMany
    {
        return $this->hasMany(DeliverySchedule::class, 'so_number', 'so_number')
                    ->where('line_number', $this->line_number);
    }

    /**
     * Get the total scheduled quantity for this line item.
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
        return $this->order_quantity - $this->total_scheduled_quantity;
    }

    /**
     * Check if the line item is fully scheduled.
     */
    public function isFullyScheduled(): bool
    {
        return $this->remaining_quantity <= 0;
    }
}
