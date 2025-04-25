<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'pd_code',
        'pd_name',
        'product_code',
        'dimension',
        'idc'
    ];
} 