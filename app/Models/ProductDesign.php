<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'pd_code',
        'pd_name',
        'pd_design_type',
        'idc',
        'product',
        'joint',
        'joint_to_print',
        'pcs_to_joint',
        'score',
        'slot',
        'flute_style',
        'print_flute',
        'input_weight',
    ];

    /**
     * Get the product associated with the product design.
     */
    public function productDetail()
    {
        return $this->belongsTo(Product::class, 'product', 'product_code');
    }
} 