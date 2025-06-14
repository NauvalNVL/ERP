<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputationFormula extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'diecut_computation_formulas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'board_length_min',
        'board_length_max',
        'board_width_min',
        'board_width_max',
        'dc_sheet_length_min',
        'dc_sheet_length_max',
        'dc_sheet_width_min',
        'dc_sheet_width_max',
        'no_of_up_min',
        'no_of_up_max',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'board_length_min' => 'integer',
        'board_length_max' => 'integer',
        'board_width_min' => 'integer',
        'board_width_max' => 'integer',
        'dc_sheet_length_min' => 'integer',
        'dc_sheet_length_max' => 'integer',
        'dc_sheet_width_min' => 'integer',
        'dc_sheet_width_max' => 'integer',
        'no_of_up_min' => 'integer',
        'no_of_up_max' => 'integer',
    ];
} 