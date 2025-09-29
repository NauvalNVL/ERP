<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliverySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'line_number',
        'schedule_sequence',
        'schedule_date',
        'schedule_time',
        'delivery_quantity',
        'due_status',
        'remark',
        'delivery_code',
        'delivery_location',
        'is_delivered',
        'actual_delivery_date',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule_time' => 'datetime:H:i',
        'delivery_quantity' => 'decimal:2',
        'is_delivered' => 'boolean',
        'actual_delivery_date' => 'date',
    ];

    /**
     * Get the sales order that owns this delivery schedule.
     */
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'so_number', 'so_number');
    }

    /**
     * Get the sales order detail that owns this delivery schedule.
     */
    public function salesOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SalesOrderDetail::class, 'so_number', 'so_number')
                    ->where('line_number', $this->line_number);
    }

    /**
     * Scope to get schedules by due status.
     */
    public function scopeByDueStatus($query, $status)
    {
        return $query->where('due_status', $status);
    }

    /**
     * Scope to get schedules by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('schedule_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get pending deliveries.
     */
    public function scopePending($query)
    {
        return $query->where('is_delivered', false);
    }

    /**
     * Scope to get delivered schedules.
     */
    public function scopeDelivered($query)
    {
        return $query->where('is_delivered', true);
    }
}
