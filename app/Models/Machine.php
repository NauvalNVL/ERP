<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machine';

    protected $fillable = [
        'machine_code',
        'machine_name',
        'process',
        'sub_process',
        'resource_type',
        'finisher_type'
    ];

    protected $casts = [
        'track_option_yz' => 'boolean',
        'track_option_bypass' => 'boolean'
    ];
}