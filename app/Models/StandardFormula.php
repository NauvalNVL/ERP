<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardFormula extends Model
{
    use HasFactory;

    protected $fillable = [
        'activate_standard_formula',
        'economic_run_size',
        'check_run_size_result',
        'master_card',
        'sales_order',
        'work_order',
    ];

    protected $casts = [
        'check_run_size_result' => 'boolean',
    ];
} 