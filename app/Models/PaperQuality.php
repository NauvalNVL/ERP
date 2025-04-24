<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperQuality extends Model
{
    use HasFactory;

    protected $table = 'paper_qualities';
    
    protected $fillable = [
        'paper_quality',
        'paper_name',
        'weight_kg_m',
        'commercial_code',
        'wet_end_code',
        'decc_code',
        'status',
        'flute',
        'db',
        'b',
        'il',
        'a_c_e',
        '2l',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weight_kg_m' => 'decimal:4',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Relationship with other models can be added here
}
