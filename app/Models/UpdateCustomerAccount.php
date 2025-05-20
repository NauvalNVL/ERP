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
        'salesperson',
        'ac_type',
        'currency',
        'status',
    ];
}
