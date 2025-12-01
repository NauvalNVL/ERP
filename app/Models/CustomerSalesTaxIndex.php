<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSalesTaxIndex extends Model
{
    use HasFactory;

    protected $table = 'customer_sales_tax_indices';

    protected $fillable = [
        'customer_code',
        'index_number',
        'tax_group_code',
        'status',
    ];

    protected $casts = [
        'index_number' => 'integer',
    ];

    /**
     * Get the customer for this tax index.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'CODE');
    }

    /**
     * Get the tax group for this tax index.
     */
    public function taxGroup()
    {
        return $this->belongsTo(TaxGroup::class, 'tax_group_code', 'code');
    }
}
