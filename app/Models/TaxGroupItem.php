<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxGroupItem extends Model
{
    use HasFactory;

    protected $table = 'tax_group_items';

    protected $fillable = [
        'tax_group_code',
        'tax_type_code',
        'sequence',
        'apply',
        'include',
        'status',
    ];

    public function taxGroup()
    {
        return $this->belongsTo(TaxGroup::class, 'tax_group_code', 'code');
    }

    public function taxType()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_code', 'code');
    }
}
