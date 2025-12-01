<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTaxProductTieup extends Model
{
    use HasFactory;

    protected $table = 'customer_tax_product_tieups';

    protected $fillable = [
        'customer_code',
        'index_number',
        'product_group_code',
        'tie_up_enabled',
    ];

    protected $casts = [
        'index_number' => 'integer',
        'tie_up_enabled' => 'boolean',
    ];

    /**
     * Get the customer sales tax index.
     */
    public function customerSalesTaxIndex()
    {
        return $this->belongsTo(CustomerSalesTaxIndex::class, ['customer_code', 'index_number'], ['customer_code', 'index_number']);
    }

    /**
     * Get the customer for this tie-up.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'CODE');
    }

    /**
     * Get the product group.
     */
    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_code', 'product_group_id');
    }
}
