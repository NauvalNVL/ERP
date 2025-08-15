<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkuPrice extends Model
{
    protected $fillable = [
        'sku_code',
        'price',
        'currency_code',
        'effective_date',
        'is_active',
        'price_type',
        'po_status',
        'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'effective_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the SKU that owns the price.
     */
    public function sku(): BelongsTo
    {
        return $this->belongsTo(MmSku::class, 'sku_code', 'sku');
    }

    /**
     * Get the currency that owns the price.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(ForeignCurrency::class, 'currency_code', 'currency_code');
    }

    /**
     * Scope a query to only include active prices.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include prices for a specific SKU.
     */
    public function scopeForSku($query, $skuCode)
    {
        return $query->where('sku_code', $skuCode);
    }

    /**
     * Scope a query to only include prices for a specific currency.
     */
    public function scopeForCurrency($query, $currencyCode)
    {
        return $query->where('currency_code', $currencyCode);
    }

    /**
     * Scope a query to only include prices for a specific price type.
     */
    public function scopeForPriceType($query, $priceType)
    {
        return $query->where('price_type', $priceType);
    }

    /**
     * Scope a query to only include prices for a specific PO status.
     */
    public function scopeForPoStatus($query, $poStatus)
    {
        return $query->where('po_status', $poStatus);
    }
}
