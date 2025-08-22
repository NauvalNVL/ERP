<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'vendor_name',
        'ap_ac_number',
        'status',
        'ac_type',
        'currency',
        'gl_ap_control',
        'gl_bank_control',
        'contact_person',
        'phone',
        'email',
        'address',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('vendor_name', 'like', "%{$search}%")
              ->orWhere('ap_ac_number', 'like', "%{$search}%");
        });
    }
}
