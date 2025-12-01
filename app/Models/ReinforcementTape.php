<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReinforcementTape extends Model
{
    use HasFactory;

    protected $table = 'reinforcement_tapes';

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
     * Scope to get only active reinforcement tapes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function mcs()
    {
        return $this->hasMany(Mc::class, 'TAPE', 'code');
    }
}
