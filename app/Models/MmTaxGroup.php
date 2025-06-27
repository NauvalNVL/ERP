<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmTaxGroup extends Model
{
    use HasFactory;

    protected $table = 'mm_tax_groups';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get the tax types that belong to this group
     */
    public function taxTypes()
    {
        return $this->hasMany(MmTaxType::class, 'tax_group_code', 'code');
    }
} 