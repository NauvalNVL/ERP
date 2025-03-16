<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'geo';

    // Specify the fillable fields
    protected $fillable = [
        'code',
        'country',
        'state',
        'town',
        'town_section',
        'area',
    ];
}
