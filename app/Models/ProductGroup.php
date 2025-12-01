<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    use HasFactory;

    protected $table = 'product_groups';
    
    protected $fillable = [
        'product_group_id',
        'product_group_name',
        'status'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    public function customerTaxProductTieups()
    {
        return $this->hasMany(CustomerTaxProductTieup::class, 'product_group_code', 'product_group_id');
    }
}
