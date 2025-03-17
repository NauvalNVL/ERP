<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperFlute extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'tur_l2b',
        'tur_l3',
        'tur_l1',
        'tur_ace',
        'tur_2l',
        'flute_height',
        'starch_consumption',
        'is_active'
    ];
}