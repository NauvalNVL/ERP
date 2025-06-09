<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAlternateAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_name',
        'customer_code',
        'salesperson_type',
        'currency',
        'currency_code',
        'status',
        'address',
        'city',
        'postal_code',
        'phone',
        'email',
    ];

    /**
     * Get the customer that owns the alternate address.
     */
    public function customer()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }
}
