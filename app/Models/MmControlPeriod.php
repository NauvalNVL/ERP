<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmControlPeriod extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mm_control_periods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pr_forward_period',
        'pr_control_date',
        'po_current_period_month',
        'po_current_period_year',
        'po_forward_period',
        'po_control_date',
        'po_min_allow_percentage',
        'po_max_allow_percentage',
        'po_zero_po',
        'inv_current_period_month',
        'inv_current_period_year',
        'inv_backward_period',
        'inv_control_date',
        'inv_zero_tran',
        'cost_current_period_month',
        'cost_current_period_year',
        'cost_control_date',
        'cost_y_allow_after_period',
        'fg_entry_date',
        'do_entry_date',
        'do_rejection_entry_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'po_min_allow_percentage' => 'float',
        'po_max_allow_percentage' => 'float',
        'cost_y_allow_after_period' => 'boolean',
    ];
} 