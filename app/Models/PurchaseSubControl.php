<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseSubControl extends Model
{
    use HasFactory;

    protected $table = 'purchase_sub_controls';

    protected $fillable = [
        'psc_code',
        'psc_name',
        'category'
    ];

    /**
     * Get the display name for the model
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->psc_code} - {$this->psc_name}";
    }



    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for searching by code or name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('psc_code', 'like', "%{$search}%")
              ->orWhere('psc_name', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    /**
     * Get all unique categories
     */
    public static function getCategories()
    {
        return static::distinct()->pluck('category')->filter()->sort()->values();
    }
} 