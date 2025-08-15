<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuItemNoteAnalysisGroup extends Model
{
    use HasFactory;

    protected $table = 'sku_item_note_analysis_groups';

    protected $fillable = [
        'group_code',
        'group_name',
        'description',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the display name for the model
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->group_code} - {$this->group_name}";
    }

    /**
     * Scope for active records
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive records
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
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
            $q->where('group_code', 'like', "%{$search}%")
              ->orWhere('group_name', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Get all unique categories
     */
    public static function getCategories()
    {
        return static::distinct()->pluck('category')->filter()->sort()->values();
    }

    /**
     * Get the analysis codes that belong to this group
     */
    public function analysisCodes()
    {
        return $this->hasMany(SkuItemNoteAnalysisCode::class, 'analysis_group_id');
    }

    /**
     * Get only active analysis codes
     */
    public function activeAnalysisCodes()
    {
        return $this->hasMany(SkuItemNoteAnalysisCode::class, 'analysis_group_id')->where('is_active', true);
    }
} 