<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'account_number',
        'dept',
        'sub_dept',
        'name',
        'status',
        'ac_type',
        'control_ac',
    ];

    public function glDistributions()
    {
        return $this->hasMany(MmGlDistribution::class, 'gl_account', 'account_number');
    }
}
