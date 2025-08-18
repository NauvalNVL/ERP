<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class MmSku extends Model
{
    protected $primaryKey = 'sku';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sku',
        'sts',
        'sku_name',
        'category_code',
        'type',
        'uom',
        'boh',
        'fpo',
        'rol',
        'total_part',
        'min_qty',
        'max_qty',
        'additional_name',
        'part_number1',
        'part_number2',
        'part_number3',
        'is_active',
        'is_locked',
        'locked_by',
        'locked_at',
        'lock_reason',
        'lock_session_id'
    ];

    protected $casts = [
        'boh' => 'decimal:3',
        'fpo' => 'decimal:3',
        'rol' => 'decimal:3',
        'min_qty' => 'decimal:2',
        'max_qty' => 'decimal:2',
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MmCategory::class, 'category_code', 'code');
    }

    public function skuType(): BelongsTo
    {
        return $this->belongsTo(SkuType::class, 'type', 'code');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(SkuPrice::class, 'sku_code', 'sku');
    }

    public function currentPrice()
    {
        return $this->hasOne(SkuPrice::class, 'sku_code', 'sku')
                    ->where('effective_date', '<=', now())
                    ->orderByDesc('effective_date')
                    ->latest()
                    ->where('is_active', true);
    }

    /**
     * Check if SKU is locked
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * Lock the SKU
     */
    public function lock(string $reason, string $sessionId = null): void
    {
        $this->update([
            'is_locked' => true,
            'locked_by' => Auth::check() ? (Auth::user()->name ?? 'System') : 'System',
            'locked_at' => now(),
            'lock_reason' => $reason,
            'lock_session_id' => $sessionId
        ]);
    }

    /**
     * Unlock the SKU
     */
    public function unlock(): void
    {
        $this->update([
            'is_locked' => false,
            'locked_by' => null,
            'locked_at' => null,
            'lock_reason' => null,
            'lock_session_id' => null
        ]);
    }

    /**
     * Get lock duration in minutes
     */
    public function getLockDurationAttribute(): int
    {
        if (!$this->locked_at) {
            return 0;
        }
        
        return $this->locked_at->diffInMinutes(now());
    }

    /**
     * Check if lock is stale (older than 30 minutes)
     */
    public function isLockStale(): bool
    {
        return $this->locked_at && $this->locked_at->diffInMinutes(now()) > 30;
    }
} 