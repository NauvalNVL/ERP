<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'grouping',
    ];
}
