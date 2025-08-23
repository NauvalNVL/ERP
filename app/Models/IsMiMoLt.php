<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class IsMiMoLt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'is_mi_mo_lt_transactions';

    protected $fillable = [
        'transaction_number',
        'transaction_type',
        'sku_code',
        'location_code',
        'category_code',
        'quantity',
        'unit_price',
        'total_amount',
        'description',
        'transaction_date',
        'reference_number',
        'notes',
        'status',
        'created_by',
        'updated_by',
        'posted_by',
        'posted_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'posted_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    // Relationships
    public function sku()
    {
        return $this->belongsTo(MmSku::class, 'sku_code', 'sku');
    }

    public function location()
    {
        return $this->belongsTo(MmLocation::class, 'location_code', 'code');
    }

    public function category()
    {
        return $this->belongsTo(MmCategory::class, 'category_code', 'code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Scopes
    public function scopeIs($query)
    {
        return $query->where('transaction_type', 'IS');
    }

    public function scopeMi($query)
    {
        return $query->where('transaction_type', 'MI');
    }

    public function scopeMo($query)
    {
        return $query->where('transaction_type', 'MO');
    }

    public function scopeLt($query)
    {
        return $query->where('transaction_type', 'LT');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'Posted');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'Cancelled');
    }

    public function scopeByDateRange($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Draft' => 'bg-gray-100 text-gray-800',
            'Posted' => 'bg-green-100 text-green-800',
            'Cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTransactionTypeBadgeClassAttribute()
    {
        return match($this->transaction_type) {
            'IS' => 'bg-blue-100 text-blue-800',
            'MI' => 'bg-purple-100 text-purple-800',
            'MO' => 'bg-orange-100 text-orange-800',
            'LT' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedTransactionDateAttribute()
    {
        return $this->transaction_date ? $this->transaction_date->format('d/m/Y') : '';
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }

    // Static methods
    public static function generateTransactionNumber($type)
    {
        $prefix = $type;
        $year = date('Y');
        $month = date('m');
        
        $lastTransaction = self::where('transaction_number', 'like', "{$prefix}{$year}{$month}%")
            ->where('status', '!=', 'Cancelled')
            ->orderBy('transaction_number', 'desc')
            ->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s%s%s%04d', $prefix, $year, $month, $newNumber);
    }

    // Methods
    public function canBeCancelled()
    {
        return $this->status !== 'Posted';
    }

    public function canBePosted()
    {
        return $this->status === 'Draft';
    }

    public function canBeEdited()
    {
        return $this->status === 'Draft';
    }

    public function isPosted()
    {
        return $this->status === 'Posted';
    }

    public function isCancelled()
    {
        return $this->status === 'Cancelled';
    }

    public function isDraft()
    {
        return $this->status === 'Draft';
    }
}
