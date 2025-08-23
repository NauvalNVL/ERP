<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcRt extends Model
{
    use HasFactory;

    protected $table = 'rc_rt_transactions';

    protected $fillable = [
        'transaction_number',
        'transaction_type', // 'RC' for Receipt, 'RT' for Return
        'transaction_date',
        'supplier_code',
        'supplier_name',
        'po_number',
        'sku_code',
        'sku_name',
        'quantity',
        'unit_price',
        'total_amount',
        'location_code',
        'location_name',
        'status', // 'Draft', 'Posted', 'Cancelled'
        'remarks',
        'created_by',
        'approved_by',
        'approved_at',
        'posted_by',
        'posted_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
        'posted_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Vendor::class, 'supplier_code', 'ap_ac_number');
    }

    public function sku()
    {
        return $this->belongsTo(MmSku::class, 'sku_code', 'sku');
    }

    public function location()
    {
        return $this->belongsTo(MmLocation::class, 'location_code', 'code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
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
    public function scopeReceipts($query)
    {
        return $query->where('transaction_type', 'RC');
    }

    public function scopeReturns($query)
    {
        return $query->where('transaction_type', 'RT');
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

    public function scopePending($query)
    {
        return $query->where('status', 'Draft');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Draft' => 'bg-gray-100 text-gray-800',
            'Posted' => 'bg-green-100 text-green-800',
            'Cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match($this->transaction_type) {
            'RC' => 'bg-blue-100 text-blue-800',
            'RT' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedDateAttribute()
    {
        return $this->transaction_date->format('d/m/Y');
    }
}
