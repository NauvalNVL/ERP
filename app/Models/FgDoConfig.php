<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FgDoConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'lock_customer_location',
        'whse_location_normal',
        'whse_location_excess',
        'whse_location_transit',
        'stock_in_normal',
        'stock_in_booking',
        'stock_out_normal',
        'stock_out_booking',
        'warehouse_transfer',
        'print_stock_in',
        'print_stock_out',
        'print_w_transfer',
        'keep_fg_records_for',
    ];
}
