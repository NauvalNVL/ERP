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
        'control_ac',
    ];
}
