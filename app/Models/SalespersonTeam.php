<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalespersonTeam extends Model
{
    use HasFactory;

    protected $table = 'salesperson_teams';
    
    protected $fillable = [
        's_person_code',
        'salesperson_name',
        'st_code',
        'sales_team_name',
        'sales_team_position',
        'status',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with SalesTeam
    public function salesTeam()
    {
        return $this->belongsTo(SalesTeam::class, 'st_code', 'st_code');
    }

    // Relationship with Salesperson
    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class, 's_person_code', 's_person_code');
    }
}
