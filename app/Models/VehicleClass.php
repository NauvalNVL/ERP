<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleClass extends Model
{
    use HasFactory;

    protected $table = 'vehicleclass';

    protected $fillable = [
        'NO_',
        'VEHICLE_CLASS_CODE',
        'DESCRIPTION',
        'STANDART_CLASS_CODE',
        'VOLUME_M3',
        'CAPACITY_WGT_MT'
    ];

    protected $casts = [
        'VOLUME_M3' => 'float',
        'CAPACITY_WGT_MT' => 'float'
    ];


    /**
     * Scope for searching vehicle classes
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('VEHICLE_CLASS_CODE', 'like', "%{$search}%")
              ->orWhere('DESCRIPTION', 'like', "%{$search}%");
        });
    }
}
