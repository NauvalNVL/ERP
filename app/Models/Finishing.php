<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'is_compute', 'is_active'];
    
    protected $casts = [
        'is_compute' => 'boolean',
        'is_active' => 'boolean',
    ];
}
