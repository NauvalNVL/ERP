<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChemicalCoat extends Model
{
    use HasFactory;

    protected $table = 'chemical_coats';

    protected $fillable = [
        'code',
        'name',
        'dry_end_code',
        'status',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active chemical coats.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
