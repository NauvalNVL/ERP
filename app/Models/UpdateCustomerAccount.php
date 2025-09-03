<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateCustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'short_name',
        'address',
        'contact_person',
        'telephone_no',
        'fax_no',
        'co_email',
        'credit_limit',
        'credit_terms',
        'ac_type',
        'currency_code',
        'salesperson_code',
        'industrial_code',
        'geographical',
        'grouping_code',
        'print_ar_aging',
        'salesperson',
        'currency',
        'status'
    ];

    // Relationship with MasterCard
    public function masterCards()
    {
        return $this->hasMany(MasterCard::class, 'customer_code', 'customer_code');
    }
}
