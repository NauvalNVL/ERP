<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuReorderLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku_id', 'period', 'min_level', 'max_level', 'reorder_level'
    ];

    public function sku()
    {
        return $this->belongsTo(MmSku::class, 'sku_id');
    }
} 