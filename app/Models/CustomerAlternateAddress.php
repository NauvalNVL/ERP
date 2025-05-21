<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAlternateAddress extends Model
{
    protected $table = 'customer_alternate_addresses';
    
    protected $fillable = [
        'customer_code',
        'address',
        'contact_person',
        'telephone_no',
        'fax_no',
        'email'
    ];
}
