<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorGroup extends Model
{
    protected $fillable = [
        'cg', 
        'cg_name', 
        'cg_type'
    ];

    // Relasi dengan warna jika diperlukan
    public function colors()
    {
        return $this->hasMany(Color::class, 'color_group_id');
    }
} 