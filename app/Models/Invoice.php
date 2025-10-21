<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'INV';
    public $timestamps = false;

    protected $guarded = [];

    // Relationship to customer account
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'AC_NUM', 'CODE');
    }

    // Scope for filtering by period
    public function scopeForPeriod($query, $year, $month)
    {
        return $query->where('YYYY', $year)->where('MM', $month);
    }

    // Scope for filtering by customer
    public function scopeForCustomer($query, $customerCode)
    {
        return $query->where('AC_NUM', $customerCode);
    }

    // Scope for filtering by status
    public function scopeWithStatus($query, $status)
    {
        return $query->where('IV_STS', $status);
    }
}
