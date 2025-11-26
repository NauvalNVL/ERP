<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrappingMaterial extends Model
{
    use HasFactory;

    protected $table = 'wrapping_materials';

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active wrapping materials
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
