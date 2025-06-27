<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'to_lock_delivery_order',
        'to_lock_stock_out_adjustment',
        'to_lock_warehouse_transfer',
    ];

    protected $casts = [
        'to_lock_delivery_order' => 'boolean',
        'to_lock_stock_out_adjustment' => 'boolean',
        'to_lock_warehouse_transfer' => 'boolean',
    ];
} 