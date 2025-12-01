<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    use HasFactory;

    // Actual database table has uppercase columns
    protected $table = 'GEO';
    protected $primaryKey = 'CODE';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // No timestamps - matching migration and CPS structure

    protected $fillable = [
        'CODE',
        'COUNTRY',
        'STATE',
        'TOWN',
        'TOWN_SECTION',
        'AREA',
        'STATUS',
    ];

    // Accessors for lowercase attribute names (for Vue compatibility)
    protected $appends = ['code', 'country', 'state', 'town', 'town_section', 'area', 'status'];

    public function getCodeAttribute()
    {
        return $this->attributes['CODE'] ?? null;
    }

    public function getCountryAttribute()
    {
        return $this->attributes['COUNTRY'] ?? null;
    }

    public function getStateAttribute()
    {
        return $this->attributes['STATE'] ?? null;
    }

    public function getTownAttribute()
    {
        return $this->attributes['TOWN'] ?? null;
    }

    public function getTownSectionAttribute()
    {
        return $this->attributes['TOWN_SECTION'] ?? null;
    }

    public function getAreaAttribute()
    {
        return $this->attributes['AREA'] ?? null;
    }

    // Mutators for lowercase attribute names
    public function setCodeAttribute($value)
    {
        $this->attributes['CODE'] = $value;
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['COUNTRY'] = $value;
    }

    public function setStateAttribute($value)
    {
        $this->attributes['STATE'] = $value;
    }

    public function setTownAttribute($value)
    {
        $this->attributes['TOWN'] = $value;
    }

    public function setTownSectionAttribute($value)
    {
        $this->attributes['TOWN_SECTION'] = $value;
    }

    public function setAreaAttribute($value)
    {
        $this->attributes['AREA'] = $value;
    }

    public function getStatusAttribute()
    {
        return $this->attributes['STATUS'] ?? 'Act';
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['STATUS'] = $value;
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'AREA', 'CODE');
    }
}
