<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    use HasFactory;

    // CPS GEO table configuration
    protected $table = 'GEO';
    protected $primaryKey = 'CODE';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'CODE',
        'COUNTRY',
        'STATE',
        'TOWN',
        'TOWN_SECTION',
        'AREA',
    ];

    // Accessors for backward compatibility with lowercase field names
    protected $appends = ['code', 'country', 'state', 'town', 'town_section', 'area'];

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
}
