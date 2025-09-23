<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'customer_code',
        'customer_name',
        'customer_address',
        'credit_terms',
        'credit_limit',
        'salesperson_code',
        'currency',
        'exchange_rate',
        'master_card_seq',
        'master_card_model',
        'p_design',
        'uom',
        'order_mode',
        'product_code',
        'order_group',
        'order_type',
        'sales_tax',
        'lot_number',
        'customer_po_number',
        'po_date',
        'remark',
        'instruction1',
        'instruction2',
        'status',
        'created_by',
    ];
}


