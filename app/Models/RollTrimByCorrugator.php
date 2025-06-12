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
        'corrugator_name',
        'flute_code',
        'trim_value',
    ];

    /**
     * Get the paper flute that this roll trim belongs to.
     */
    public function paperFlute()
    {
        return $this->belongsTo(PaperFlute::class, 'flute_code', 'code');
    }
} 