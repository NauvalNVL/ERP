<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApproveMC extends Model
{
    protected $table = 'approve_mcs';
    
    protected $fillable = [
        'mc_seq',
        'mc_model',
        'customer_code',
        'customer_name',
        'status',
        'approved_by',
        'approved_date',
        'rejected_by',
        'rejected_date',
        'rejection_reason'
    ];
    
    public function customer()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }
}
