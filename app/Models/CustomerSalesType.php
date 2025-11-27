<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class CustomerSalesType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer_sales_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_code',
        'customer_name',
        'sales_type',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the customer that owns the sales type.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'CODE');
    }
}



