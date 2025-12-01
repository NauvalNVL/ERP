<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringTool extends Model
{
    use HasFactory;

    // Simplified to match CPS structure: NO., CODE, NAME, SCORER GAP
    protected $fillable = [
        'code',
        'name',
        'scorer_gap',
        'status',
    ];

    protected $casts = [
        'scorer_gap' => 'float',
    ];
    
    // Disable timestamps
    public $timestamps = false;

    public function mcs()
    {
        return $this->hasMany(Mc::class, 'S_TOOL', 'code');
    }
}
