<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colors';
    protected $primaryKey = 'color_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'color_id',
        'color_name',
        'origin',
        'color_group_id',
        'cg_type',
    ];
}
