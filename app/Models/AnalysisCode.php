<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisCode extends Model
{
    use HasFactory;

    protected $table = 'analysis_codes';
    protected $primaryKey = 'analysis_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'analysis_code',
        'analysis_name',
        'analysis_group',
        'analysis_group2',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
