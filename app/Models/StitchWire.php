<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StitchWire extends Model
{
    use HasFactory;

    protected $table = 'stitch_wires';

    protected $fillable = [
        'code',
        'name',
        'status',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active stitch wires.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
