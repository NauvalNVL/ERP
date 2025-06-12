<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrugatorConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'corrugator_id',
        'min_sheet_length',
        'max_sheet_length',
        'min_sheet_width',
        'max_sheet_width',
        'is_sheet_length_multiplied',
        'is_min_raw_multiplied',
        'validate_sheet_width'
    ];

    protected $casts = [
        'is_sheet_length_multiplied' => 'boolean',
        'is_min_raw_multiplied' => 'boolean',
        'validate_sheet_width' => 'boolean',
    ];
} 