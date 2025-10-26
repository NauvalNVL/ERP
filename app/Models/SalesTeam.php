<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Salesperson;

class SalesTeam extends Model
{
    use HasFactory;

    // Definisikan nama tabel yang benar sesuai dengan seeder
    protected $table = 'sales_team';

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Boot method to register model events
     */
    protected static function boot()
    {
        parent::boot();

        // When sales team is created, updated, or deleted
        static::created(function ($salesTeam) {
            Salesperson::syncSalespersonTeams();
        });

        static::updated(function ($salesTeam) {
            Salesperson::syncSalespersonTeams();
        });

        static::deleted(function ($salesTeam) {
            Salesperson::syncSalespersonTeams();
        });
    }

    /**
     * Get the salespersons for the sales team.
     */
    public function salespersons()
    {
        return $this->hasMany(Salesperson::class, 'sales_team_id');
    }
} 