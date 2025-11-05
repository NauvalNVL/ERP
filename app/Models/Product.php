<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'products';
    
    protected $fillable = [
        'product_code',
        'description',
        'product_group_id',
        'category',
        'unit',
        'is_active'
    ];

    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id', 'product_group_id');
    }
    
    /**
     * Get the corrugator specification for this product.
     */
    public function corrugatorSpec()
    {
        return $this->hasOne(CorrugatorSpecByProduct::class);
    }
}
