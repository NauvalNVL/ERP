<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    use HasFactory;

    // Actual database table has lowercase columns
    protected $table = 'GEO';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // No timestamps - matching migration and CPS structure

    protected $fillable = [
        'code',
        'country',
        'state',
        'town',
        'town_section',
        'area',
    ];
}
