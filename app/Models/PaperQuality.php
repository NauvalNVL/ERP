<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperQuality extends Model
{
    use HasFactory;

    protected $table = 'paper_qualities';
    
    protected $fillable = [
        'code',
        'name',
        'description',
        'gsm',
        'paper_type',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gsm' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Relationship with other models can be added here
}
