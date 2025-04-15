<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_code',
        'design_name',
        'product_code',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
} 