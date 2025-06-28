<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SideTrimByFlute extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'side_trims_by_flute';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flute_id',
        'length_add',
        'length_less',
        'compute',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'compute' => 'boolean',
        'length_add' => 'integer',
        'length_less' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the paper flute associated with the side trim.
     */
    public function paperFlute(): BelongsTo
    {
        return $this->belongsTo(PaperFlute::class, 'flute_id');
    }
}