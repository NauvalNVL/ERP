<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ColorGroup;

class Color extends Model
{
    use HasFactory;

    protected $table = 'COLOR';
    protected $primaryKey = 'Color_Code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Color_Code',
        'Color_Name',
        'GroupCode',
        'Group',
        'status'
    ];

    /**
     * Scope to get only color groups (where GroupCode is NULL or equals Color_Code)
     */
    public function scopeColorGroups($query)
    {
        return $query->whereNull('GroupCode')
                    ->orWhereRaw('GroupCode = Color_Code');
    }

    /**
     * Scope to get only colors (where GroupCode is not NULL and not equal to Color_Code)
     */
    public function scopeColors($query)
    {
        return $query->whereNotNull('GroupCode')
                    ->whereRaw('GroupCode != Color_Code');
    }

    /**
     * Check if this record is a color group
     */
    public function isColorGroup()
    {
        return $this->GroupCode === null || $this->GroupCode === $this->Color_Code;
    }

    /**
     * Check if this record is a color
     */
    public function isColor()
    {
        return !$this->isColorGroup();
    }

    /**
     * Get the parent color group for this color
     */
    public function colorGroup()
    {
        return $this->belongsTo(ColorGroup::class, 'GroupCode', 'CG');
    }

    /**
     * Get all colors that belong to this color group
     */
    public function colors()
    {
        return $this->hasMany(Color::class, 'GroupCode', 'Color_Code')
                    ->where('GroupCode', '!=', 'Color_Code');
    }
}
