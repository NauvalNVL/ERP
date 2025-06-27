<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmConfig extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rc_ap_remarks' => 'array',
        'rc_gl_remarks' => 'array',
        'rt_ap_remarks' => 'array',
        'rt_gl_remarks' => 'array',
        'dn_gl_remarks' => 'array',
        'cn_gl_remarks' => 'array',
        'is_gl_remarks' => 'array',
        'mi_gl_remarks' => 'array',
        'mo_gl_remarks' => 'array',
    ];
}
