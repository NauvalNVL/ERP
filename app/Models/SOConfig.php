<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOConfig extends Model
{
    use HasFactory;

    protected $table = 'so_configs'; // Define the table name

    protected $fillable = [
        'activate_order_type',
        'check_roll_balance',
        'roll_stock_balance_check',
        'compute_roll_stock_balance',
        'compute_wip_sales_orders',
        'jit_so_po_no',
    ];

    protected $casts = [
        'activate_order_type' => 'array',
        'roll_stock_balance_check' => 'array',
        'compute_roll_stock_balance' => 'integer',
        'compute_wip_sales_orders' => 'integer',
    ];
} 