<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrugatorSpecByProduct extends Model
{
    use HasFactory;

    protected $table = 'corrugator_spec_by_products';

    protected $fillable = [
        'product_code',
        'compute',
        'min_sheet_length',
        'max_sheet_length',
        'min_sheet_width',
        'max_sheet_width'
    ];

    protected $casts = [
        'compute' => 'boolean',
        'min_sheet_length' => 'integer',
        'max_sheet_length' => 'integer',
        'min_sheet_width' => 'integer',
        'max_sheet_width' => 'integer',
    ];

    /**
     * Get the product that owns the specification.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
} 