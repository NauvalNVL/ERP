<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Salesperson extends Model
{
    use HasFactory;

    // Set the table name explicitly to match the migration
    protected $table = 'salesperson';

    protected $fillable = [
        'Code',
        'Name',
        'Grup',
        'CodeGrup',
        'TargetSales',
        'Internal',
        'Email',
        'status'
    ];

    protected $casts = [
        'TargetSales' => 'decimal:2',
        'status' => 'string', // This will auto-trim CHAR fields
    ];

    /**
     * Boot method to register model events
     */
    protected static function boot()
    {
        parent::boot();

        // When salesperson is created, updated, or deleted
        static::created(function ($salesperson) {
            self::syncSalespersonTeams();
        });

        static::updated(function ($salesperson) {
            self::syncSalespersonTeams();
        });

        static::deleted(function ($salesperson) {
            self::syncSalespersonTeams();
        });
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'code',
        'name',
        'sales_team_id',
        'position',
        'user_id',
        'is_active'
    ];

    /**
     * Accessor methods for backward compatibility with Vue components
     */
    public function getCodeAttribute()
    {
        return $this->attributes['Code'] ?? null;
    }

    public function getNameAttribute()
    {
        return $this->attributes['Name'] ?? null;
    }

    public function getSalesTeamIdAttribute()
    {
        // Map Grup to a team ID for compatibility
        $grupMapping = [
            'MBI' => 1,
            'MANAGEMENT LOCAL' => 2,
            'MANAGEMENT MNC' => 3
        ];
        
        $grup = $this->attributes['Grup'] ?? null;
        return $grupMapping[$grup] ?? 1;
    }

    public function getPositionAttribute()
    {
        // Map status or create a default position
        return $this->attributes['status'] === 'Active' ? 'E - Executive' : 'E - Executive';
    }

    public function getUserIdAttribute()
    {
        return $this->attributes['Internal'] ?? null;
    }

    public function getStatusAttribute($value)
    {
        return trim($value ?? '');
    }

    public function getIsActiveAttribute()
    {
        $status = $this->status; // Use the trimmed status accessor
        return $status === 'Active';
    }

    /**
     * Mutator methods for setting values
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['Code'] = $value;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['Name'] = $value;
    }

    public function setSalesTeamIdAttribute($value)
    {
        // Map team ID back to Grup
        $teamMapping = [
            1 => 'MBI',
            2 => 'MANAGEMENT LOCAL',
            3 => 'MANAGEMENT MNC'
        ];
        
        $this->attributes['Grup'] = $teamMapping[$value] ?? 'MBI';
    }

    public function setPositionAttribute($value)
    {
        // Map position to status or other field as needed
        $this->attributes['status'] = 'Active';
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['Internal'] = $value;
    }

    public function setIsActiveAttribute($value)
    {
        // Ensure no trailing spaces and proper boolean conversion
        $isActive = (bool)$value;
        $this->attributes['status'] = $isActive ? 'Active' : 'Inactive';
    }

    // ==================== SALES TEAM METHODS ====================
    
    /**
     * Get all unique sales teams
     */
    public static function getAllTeams()
    {
        return self::select('Grup as name', 'CodeGrup as code')
            ->whereNotNull('Grup')
            ->distinct()
            ->orderBy('Grup')
            ->get();
    }

    /**
     * Get team members by team name
     */
    public static function getTeamMembers($teamName)
    {
        return self::where('Grup', $teamName)
            ->whereNotLike('Code', 'TEAM_%')
            ->orderBy('Name')
            ->get();
    }

    /**
     * Create or update team
     */
    public static function createOrUpdateTeam($teamCode, $teamName)
    {
        // Check if team entry exists
        $teamEntry = self::where('Code', 'TEAM_' . $teamCode)->first();
        
        if ($teamEntry) {
            $teamEntry->update([
                'Name' => 'Team: ' . $teamName,
                'Grup' => $teamName,
                'CodeGrup' => $teamCode
            ]);
        } else {
            self::create([
                'Code' => 'TEAM_' . $teamCode,
                'Name' => 'Team: ' . $teamName,
                'Grup' => $teamName,
                'CodeGrup' => $teamCode,
                'status' => 'Active'
            ]);
        }
        
        return true;
    }

    // ==================== SALESPERSON TEAM METHODS ====================
    
    /**
     * Get salesperson with team information
     */
    public static function getSalespersonTeams()
    {
        return self::select('Code', 'Name', 'Grup', 'CodeGrup', 'TargetSales', 'Internal', 'Email', 'status')
            ->whereNotNull('Grup')
            ->whereNotLike('Code', 'TEAM_%')
            ->orderBy('Grup')
            ->orderBy('Name')
            ->get();
    }

    /**
     * Assign salesperson to team
     */
    public function assignToTeam($teamName, $teamCode)
    {
        return $this->update([
            'Grup' => $teamName,
            'CodeGrup' => $teamCode
        ]);
    }

    /**
     * Remove salesperson from team
     */
    public function removeFromTeam()
    {
        return $this->update([
            'Grup' => null,
            'CodeGrup' => null
        ]);
    }

    /**
     * Check if salesperson is in a team
     */
    public function hasTeam()
    {
        return !is_null($this->Grup);
    }

    /**
     * Get team name (alias for Grup)
     */
    public function getTeamNameAttribute()
    {
        return $this->Grup;
    }

    /**
     * Get team code (alias for CodeGrup)
     */
    public function getTeamCodeAttribute()
    {
        return $this->CodeGrup;
    }

    /**
     * Sync salesperson_teams table with current salesperson and sales_team data
     */
    public static function syncSalespersonTeams()
    {
        try {
            // Clear existing salesperson_teams data
            DB::table('salesperson_teams')->truncate();

            // Get all salesperson data
            $salespersons = DB::table('salesperson')
                ->whereNotNull('Code')
                ->whereNotNull('Name')
                ->where('Code', '!=', '')
                ->where('Name', '!=', '')
                ->whereNotLike('Code', 'TEAM_%') // Exclude team entries
                ->get();

            // Get all sales_team data
            $salesTeams = DB::table('sales_team')
                ->whereNotNull('code')
                ->whereNotNull('name')
                ->where('code', '!=', '')
                ->where('name', '!=', '')
                ->get();

            if ($salespersons->isEmpty() || $salesTeams->isEmpty()) {
                return;
            }

            // Create one-to-one assignment of salesperson to sales_team
            $salespersonTeamsData = [];
            $now = now();
            $salesTeamsArray = $salesTeams->toArray();
            $teamIndex = 0;

            foreach ($salespersons as $salesperson) {
                // Assign each salesperson to one sales team (round-robin)
                $assignedTeam = $salesTeamsArray[$teamIndex % count($salesTeamsArray)];
                
                $salespersonTeamsData[] = [
                    's_person_code' => substr($salesperson->Code, 0, 10),
                    'salesperson_name' => substr($salesperson->Name, 0, 100),
                    'st_code' => substr($assignedTeam->code, 0, 2),
                    'sales_team_name' => substr($assignedTeam->name, 0, 100),
                    'sales_team_position' => 'E-Executive',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                
                $teamIndex++;
            }

            // Insert data in chunks
            if (!empty($salespersonTeamsData)) {
                $chunks = array_chunk($salespersonTeamsData, 100);
                foreach ($chunks as $chunk) {
                    DB::table('salesperson_teams')->insert($chunk);
                }
            }

        } catch (\Exception $e) {
            Log::error('Error syncing salesperson_teams: ' . $e->getMessage());
        }
    }
} 