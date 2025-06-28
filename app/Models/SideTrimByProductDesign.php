<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideTrimByProductDesign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'side_trims_by_product_design';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_design_id',
        'product_id',
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
     * Get the product design associated with the side trim.
     */
    public function productDesign()
    {
        return $this->belongsTo(ProductDesign::class, 'product_design_id');
    }

    /**
     * Get the product associated with the side trim.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the paper flute associated with the side trim.
     */
    public function paperFlute()
    {
        return $this->belongsTo(PaperFlute::class, 'flute_id');
    }
} 