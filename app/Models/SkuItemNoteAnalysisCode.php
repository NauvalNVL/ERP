<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuItemNoteAnalysisCode extends Model
{
    use HasFactory;

    protected $table = 'sku_item_note_analysis_codes';

    protected $fillable = [
        'analysis_group_id',
        'analysis_code',
        'code_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'analysis_group_id' => 'integer'
    ];

    /**
     * Get the analysis group that owns this code
     */
    public function analysisGroup()
    {
        return $this->belongsTo(SkuItemNoteAnalysisGroup::class, 'analysis_group_id');
    }

    /**
     * Get display name combining code and name
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->analysis_code} - {$this->code_name}";
    }

    /**
     * Get full display name with group
     */
    public function getFullDisplayNameAttribute(): string
    {
        $groupName = $this->analysisGroup ? $this->analysisGroup->group_name : 'Unknown Group';
        return "{$groupName} → {$this->analysis_code} - {$this->code_name}";
    }

    /**
     * Scope to get only active codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive codes
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to filter by analysis group
     */
    public function scopeByGroup($query, $groupId)
    {
        return $query->where('analysis_group_id', $groupId);
    }

    /**
     * Scope to search codes
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('analysis_code', 'like', "%{$search}%")
              ->orWhere('code_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to include group information
     */
    public function scopeWithGroup($query)
    {
        return $query->with('analysisGroup');
    }
} 