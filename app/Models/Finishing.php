<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'status'];
    
    protected $casts = [

    ];

    public function mcs()
    {
        return $this->hasMany(Mc::class, 'FSH', 'code');
    }
}
