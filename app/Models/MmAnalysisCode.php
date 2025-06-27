<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmAnalysisCode extends Model
{
    use HasFactory;

    protected $table = 'mm_analysis_codes';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'code',
        'name',
        'group',
        'group2',
    ];
} 