<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmSku extends Model
{
    use HasFactory;

    protected $table = 'mm_skus';
    protected $primaryKey = 'sku';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sku',
        'sts',
        'sku_name',
        'category_code',
        'type',
        'uom',
        'boh',
        'fpo',
        'rol',
        'total_part',
        'min_qty',
        'max_qty',
        'additional_name',
        'part_number1',
        'part_number2',
        'part_number3',
        'is_active'
    ];

    protected $casts = [
        'boh' => 'decimal:3',
        'fpo' => 'decimal:3',
        'rol' => 'decimal:3',
        'min_qty' => 'decimal:2',
        'max_qty' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(MmCategory::class, 'category_code', 'code');
    }
} 