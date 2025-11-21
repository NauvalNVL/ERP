<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveMC extends Model
{
    use HasFactory;

    protected $table = 'approve_mc';
    
    protected $fillable = [
        'mc_seq',
        'mc_model',
        'customer_code',
        'customer_name',
        'status',
        'released_date',
        'released_by',
        'notes'
    ];

    protected $casts = [
        'released_date' => 'datetime',
    ];
}
