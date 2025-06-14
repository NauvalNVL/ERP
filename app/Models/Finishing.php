<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'is_compute'];
    
    protected $casts = [
        'is_compute' => 'boolean',
    ];
}
