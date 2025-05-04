<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'color_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'color_id',
        'color_name',
        'origin',
        'color_group_id',
        'cg_type',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the color group that owns the color.
     */
    public function colorGroup()
    {
        return $this->belongsTo(ColorGroup::class, 'color_group_id', 'cg');
    }

    /**
     * Get the user that created the color.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that last updated the color.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
