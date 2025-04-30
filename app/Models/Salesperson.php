<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesperson extends Model
{
    use HasFactory;

    // Set the table name explicitly to match the migration
    protected $table = 'salesperson';

    protected $fillable = [
        'code',
        'name',
        'sales_team_id',
        'position',
        'user_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sales_team_id' => 'integer'
    ];

    /**
     * Get the sales team that the salesperson belongs to.
     */
    public function salesTeam()
    {
        return $this->belongsTo(SalesTeam::class, 'sales_team_id');
    }
} 