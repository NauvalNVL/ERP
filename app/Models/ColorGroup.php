<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorGroup extends Model
{
    use HasFactory;

    // CPS COLOR_GROUP table configuration
    protected $table = 'COLOR_GROUP';
    protected $primaryKey = 'CG';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'CG',
        'CG_Name',
        'CG_Type',
        'status'
    ];

    /**
     * Get all colors that belong to this color group
     * Relationship with COLOR table through GroupCode
     */
    public function colors()
    {
        return $this->hasMany(Color::class, 'GroupCode', 'CG');
    }
}
