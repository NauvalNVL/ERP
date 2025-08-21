<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_id',
        'line_number',
        'sku_code',
        'description',
        'specification',
        'quantity',
        'unit',
        'estimated_price',
        'total_price',
        'current_stock',
        'stock_status',
        'urgency',
        'notes',
        'approved_quantity',
        'remaining_quantity',
        'converted_quantity',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'estimated_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'current_stock' => 'decimal:3',
        'approved_quantity' => 'decimal:3',
        'remaining_quantity' => 'decimal:3',
        'converted_quantity' => 'decimal:3'
    ];

    // Constants for stock status
    const STOCK_AVAILABLE = 'available';
    const STOCK_LOW = 'low';
    const STOCK_OUT = 'out_of_stock';
    const STOCK_NOT_FOUND = 'not_found';
    const STOCK_UNKNOWN = 'unknown';
    const STOCK_ERROR = 'error';

    // Constants for urgency
    const URGENCY_LOW = 'LOW';
    const URGENCY_MEDIUM = 'MEDIUM';
    const URGENCY_HIGH = 'HIGH';
    const URGENCY_CRITICAL = 'CRITICAL';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
            
            // Auto-calculate total price
            if ($model->quantity && $model->estimated_price) {
                $model->total_price = $model->quantity * $model->estimated_price;
            }

            // Set remaining quantity equal to quantity initially
            $model->remaining_quantity = $model->quantity;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
            
            // Auto-calculate total price
            if ($model->quantity && $model->estimated_price) {
                $model->total_price = $model->quantity * $model->estimated_price;
            }
        });
    }

    /**
     * Relationships
     */
    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }

    public function sku()
    {
        return $this->belongsTo(MmSku::class, 'sku_code', 'sku');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeByPr($query, $prId)
    {
        return $query->where('pr_id', $prId);
    }

    public function scopeBySku($query, $skuCode)
    {
        return $query->where('sku_code', $skuCode);
    }

    public function scopeByStockStatus($query, $status)
    {
        return $query->where('stock_status', $status);
    }

    public function scopeByUrgency($query, $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    public function scopeWithStock($query)
    {
        return $query->where('stock_status', self::STOCK_AVAILABLE);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_status', self::STOCK_LOW);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_status', self::STOCK_OUT);
    }

    public function scopePending($query)
    {
        return $query->where('remaining_quantity', '>', 0);
    }

    public function scopeFullyConverted($query)
    {
        return $query->where('remaining_quantity', '<=', 0);
    }

    /**
     * Accessors & Mutators
     */
    public function getStockStatusBadgeAttribute()
    {
        $badges = [
            self::STOCK_AVAILABLE => ['class' => 'bg-green-100 text-green-800', 'text' => 'Available'],
            self::STOCK_LOW => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Low Stock'],
            self::STOCK_OUT => ['class' => 'bg-red-100 text-red-800', 'text' => 'Out of Stock'],
            self::STOCK_NOT_FOUND => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Not Found'],
            self::STOCK_UNKNOWN => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'],
            self::STOCK_ERROR => ['class' => 'bg-red-100 text-red-800', 'text' => 'Error'],
        ];

        return $badges[$this->stock_status] ?? $badges[self::STOCK_UNKNOWN];
    }

    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            self::URGENCY_LOW => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Low'],
            self::URGENCY_MEDIUM => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Medium'],
            self::URGENCY_HIGH => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'High'],
            self::URGENCY_CRITICAL => ['class' => 'bg-red-100 text-red-800', 'text' => 'Critical'],
        ];

        return $badges[$this->urgency] ?? $badges[self::URGENCY_MEDIUM];
    }

    /**
     * Business Logic Methods
     */
    public function updateStockInfo()
    {
        if (!$this->sku_code) {
            $this->stock_status = self::STOCK_UNKNOWN;
            return $this;
        }

        try {
            $sku = MmSku::where('sku', $this->sku_code)->first();
            
            if (!$sku) {
                $this->stock_status = self::STOCK_NOT_FOUND;
                $this->current_stock = null;
            } else {
                $this->current_stock = $sku->boh ?? 0;
                
                // Determine stock status based on current stock
                if ($this->current_stock <= 0) {
                    $this->stock_status = self::STOCK_OUT;
                } elseif ($this->current_stock < $this->quantity) {
                    $this->stock_status = self::STOCK_LOW;
                } else {
                    $this->stock_status = self::STOCK_AVAILABLE;
                }
            }
            
            $this->save();
            
        } catch (\Exception $e) {
            $this->stock_status = self::STOCK_ERROR;
            $this->save();
        }

        return $this;
    }

    public function convertToPo($convertedQuantity)
    {
        if ($convertedQuantity > $this->remaining_quantity) {
            throw new \InvalidArgumentException('Converted quantity cannot exceed remaining quantity');
        }

        $this->converted_quantity += $convertedQuantity;
        $this->remaining_quantity -= $convertedQuantity;
        $this->save();

        // Update PR status if all items are fully converted
        $this->checkPrConversionStatus();

        return $this;
    }

    public function checkPrConversionStatus()
    {
        $pr = $this->purchaseRequisition;
        $totalItems = $pr->items->count();
        $fullyConvertedItems = $pr->items->where('remaining_quantity', '<=', 0)->count();
        $partiallyConvertedItems = $pr->items->where('converted_quantity', '>', 0)
                                            ->where('remaining_quantity', '>', 0)->count();

        if ($fullyConvertedItems === $totalItems) {
            $pr->update(['status' => PurchaseRequisition::STATUS_FULLY_CONVERTED]);
        } elseif ($partiallyConvertedItems > 0 || $fullyConvertedItems > 0) {
            $pr->update(['status' => PurchaseRequisition::STATUS_PARTIALLY_CONVERTED]);
        }
    }

    public function getConversionPercentageAttribute()
    {
        if ($this->quantity <= 0) {
            return 0;
        }

        return round(($this->converted_quantity / $this->quantity) * 100, 2);
    }

    public function getRemainingPercentageAttribute()
    {
        if ($this->quantity <= 0) {
            return 0;
        }

        return round(($this->remaining_quantity / $this->quantity) * 100, 2);
    }

    /**
     * Check if item needs urgent procurement
     */
    public function needsUrgentProcurement()
    {
        return $this->stock_status === self::STOCK_OUT || 
               $this->urgency === self::URGENCY_CRITICAL ||
               ($this->stock_status === self::STOCK_LOW && $this->urgency === self::URGENCY_HIGH);
    }

    /**
     * Get procurement recommendation
     */
    public function getProcurementRecommendation()
    {
        if ($this->stock_status === self::STOCK_AVAILABLE && $this->current_stock >= $this->quantity) {
            return [
                'type' => 'transfer',
                'message' => 'Consider internal transfer from available stock',
                'priority' => 'low'
            ];
        }

        if ($this->stock_status === self::STOCK_LOW) {
            return [
                'type' => 'urgent_purchase',
                'message' => 'Urgent purchase required - low stock',
                'priority' => 'high'
            ];
        }

        if ($this->stock_status === self::STOCK_OUT) {
            return [
                'type' => 'critical_purchase',
                'message' => 'Critical purchase required - out of stock',
                'priority' => 'critical'
            ];
        }

        return [
            'type' => 'normal_purchase',
            'message' => 'Normal purchase procedure',
            'priority' => 'medium'
        ];
    }
}
