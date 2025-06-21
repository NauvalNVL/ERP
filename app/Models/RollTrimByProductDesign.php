<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollTrimByProductDesign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roll_trims_by_product_design';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_design_id',
        'flute_id',
        'compute',
        'min_trim',
        'max_trim',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'compute' => 'boolean',
        'min_trim' => 'integer',
        'max_trim' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product associated with the roll trim.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product design associated with the roll trim.
     */
    public function productDesign()
    {
        return $this->belongsTo(ProductDesign::class);
    }

    /**
     * Get the paper flute associated with the roll trim.
     */
    public function paperFlute()
    {
        return $this->belongsTo(PaperFlute::class, 'flute_id');
    }
} 