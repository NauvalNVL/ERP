<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable constraints, truncate, and re-enable constraints for SQL Server
        DB::statement('ALTER TABLE industry NOCHECK CONSTRAINT ALL');
        DB::statement('TRUNCATE TABLE industry');
        DB::statement('ALTER TABLE industry CHECK CONSTRAINT ALL');

        $industries = [
            ['code' => 'ID01', 'name' => 'AUTOMOTIVE'],
            ['code' => 'ID02', 'name' => 'BUILDING MATERIAL'],
            ['code' => 'ID03', 'name' => 'CERAMIC TILE'],
            ['code' => 'ID04', 'name' => 'CHEMICAL'],
            ['code' => 'ID05', 'name' => 'ELECTRONIC'],
            ['code' => 'ID06', 'name' => 'FOOD & BEVERAGES'],
            ['code' => 'ID07', 'name' => 'FARM'],
            ['code' => 'ID08', 'name' => 'PHARMACY'],
            ['code' => 'ID09', 'name' => 'FURNITURE'],
            ['code' => 'ID10', 'name' => 'GARMENT'],
            ['code' => 'ID11', 'name' => 'HOUSEWARE'],
            ['code' => 'ID12', 'name' => 'INDUSTRY'],
            ['code' => 'ID13', 'name' => 'PAPER'],
            ['code' => 'ID14', 'name' => 'PERSONAL CARES'],
            ['code' => 'ID15', 'name' => 'SHOES'],
            ['code' => 'ID16', 'name' => 'TEXTILE'],
            ['code' => 'ID17', 'name' => 'TOYS'],
            ['code' => 'ID18', 'name' => 'OTHER INDUSTRY']
        ];

        // Insert data using query builder instead of Eloquent
        DB::table('industry')->insert($industries);
    }
}
