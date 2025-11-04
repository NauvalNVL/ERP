<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlueingMaterial extends Model
{
    use HasFactory;

    protected $table = 'glueing_materials';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active glueing materials
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
