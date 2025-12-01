<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundlingString extends Model
{
    use HasFactory;

    protected $table = 'bundling_strings';

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
     * Scope to get only active bundling strings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function mcs()
    {
        return $this->hasMany(Mc::class, 'STRING_TYPE', 'code');
    }
}
