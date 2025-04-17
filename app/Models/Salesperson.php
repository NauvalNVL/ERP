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
        'status',
        'email',
        'phone',
        'mobile',
        'notes',
        'sales_team_id',
    ];

    /**
     * Get the sales team that the salesperson belongs to.
     */
    public function salesTeam()
    {
        return $this->belongsTo(SalesTeam::class, 'sales_team_id');
    }
} 