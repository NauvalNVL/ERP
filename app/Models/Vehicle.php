<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';

    protected $fillable = [
        'NO_',
        'VEHICLE_NO',
        'VEHICLE_STATUS',
        'VEHICLE_CLASS',
        'VEHICLE_DESCRIPTION',
        'VEHICLE_COMPANY',
        'COMPANY',
        'DRIVER_CODE',
        'DRIVER_NAME',
        'DRIVER_ID',
        'DRIVER_PHONE',
        'NOTE',
        'STATUS'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with VehicleClass
    public function vehicleClass()
    {
        return $this->belongsTo(VehicleClass::class, 'VEHICLE_CLASS', 'VEHICLE_CLASS_CODE');
    }

    // Scope for active vehicles
    public function scopeActive($query)
    {
        return $query->where('VEHICLE_STATUS', 'A');
    }

    // Scope for inactive vehicles
    public function scopeInactive($query)
    {
        return $query->where('VEHICLE_STATUS', 'O');
    }

    // Accessor for formatted vehicle status
    public function getFormattedStatusAttribute()
    {
        return $this->VEHICLE_STATUS === 'A' ? 'Active' : 'Obsolete';
    }

    // Accessor for formatted company
    public function getFormattedCompanyAttribute()
    {
        $companies = [
            'KIM' => 'KIM',
            'CUSTOMER' => 'CUSTOMER',
            'MBI' => 'MBI',
            'MMI' => 'MMI'
        ];
        
        return $companies[$this->VEHICLE_COMPANY] ?? $this->VEHICLE_COMPANY;
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'DO_VHC_Num', 'VEHICLE_NO');
    }
}
