<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmGlDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'gl_dist_code',
        'gl_dist_name',
        'gl_account',
        'gl_account_name',
        'is_linked',
        'is_active'
    ];

    protected $casts = [
        'is_linked' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'gl_account', 'account_number');
    }
} 