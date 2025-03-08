<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get the salespersons for the sales team.
     */
    public function salespersons()
    {
        return $this->hasMany(Salesperson::class, 'sales_team_id');
    }
} 