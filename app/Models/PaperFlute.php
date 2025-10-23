<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperFlute extends Model
{
    use HasFactory;

    protected $table = 'Flute_CPS';
    public $timestamps = false;
    protected $primaryKey = 'Flute';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'No',
        'Flute',
        'Descr',
        'DB',
        'B',
        '_1L',
        'A_C_E',
        '_2L',
        'Height',
        'Starch'
    ];

    protected $casts = [
        'No' => 'decimal:0',
        'DB' => 'decimal:2',
        'B' => 'decimal:2',
        '_1L' => 'decimal:2',
        'A_C_E' => 'decimal:2',
        '_2L' => 'decimal:2',
        'Height' => 'decimal:2',
        'Starch' => 'decimal:2'
    ];
}