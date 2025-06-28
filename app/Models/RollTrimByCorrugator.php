<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollTrimByCorrugator extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roll_trims_by_corrugator';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flute_id',
        'compute',
        'min_trim',
        'max_trim',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'compute' => 'boolean',
    ];

    /**
     * Get the paper flute that this roll trim belongs to.
     */
    public function paperFlute()
    {
        return $this->belongsTo(PaperFlute::class, 'flute_id');
    }
} 