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
        'customer_code',
        'delivery_code',
        'country',
        'state',
        'town',
        'town_section',
        'bill_to_name',
        'bill_to_address',
        'ship_to_name',
        'ship_to_address',
        'contact_person',
        'tel_no',
        'fax_no',
        'email',
    ];

    /**
     * Get the customer that owns the alternate address.
     */
    public function customerAccount()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }
}
