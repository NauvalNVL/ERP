<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperFlute extends Model
{
    use HasFactory;

    // CPS Flute_CPS table configuration
    protected $table = 'Flute_CPS';
    public $timestamps = false;
    protected $primaryKey = 'No';  // No is the primary key
    public $incrementing = false;  // Not auto-increment
    protected $keyType = 'integer'; // numeric(18,0) type

    protected $fillable = [
        'No',
        'Flute',
        'Descr',
        'DB',
        'B',
        '_1L',      // [1L] in database
        'A_C_E',    // [A/C/E] in database
        '_2L',      // [2L] in database
        'Height',
        'Starch'
    ];

    protected $casts = [
        'No' => 'integer',
        'DB' => 'float',
        'B' => 'float',
        '_1L' => 'float',
        'A_C_E' => 'float',
        '_2L' => 'float',
        'Height' => 'float',
        'Starch' => 'float'
    ];
}