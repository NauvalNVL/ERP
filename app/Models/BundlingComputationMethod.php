<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundlingComputationMethod extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bundling_computation_methods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name',
        'product_design',
        'product',
        'flute',
        'formula_divisor',
        'formula_pieces',
        'height_type', // 'internal', 'extended'
        'rounding_type', // 'up', 'down'
        'is_compute',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'formula_divisor' => 'integer',
        'formula_pieces' => 'integer',
        'is_compute' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
} 