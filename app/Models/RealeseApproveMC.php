<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealeseApproveMC extends Model
{
    // Actually using the same table as ApproveMC 
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
        'rejection_reason',
        'released_by',
        'released_date',
        'release_notes'
    ];
    
    public function customer()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }
    
    // Scope to get only released master cards
    public function scopeReleased($query)
    {
        return $query->whereNotNull('released_date');
    }
    
    // Scope to get only approved but not released master cards
    public function scopeApprovedNotReleased($query)
    {
        return $query->where('status', 'active')
                    ->whereNull('released_date');
    }
}
