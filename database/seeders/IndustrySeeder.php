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
        DB::statement('ALTER TABLE industries NOCHECK CONSTRAINT ALL');
        DB::statement('TRUNCATE TABLE industries');
        DB::statement('ALTER TABLE industries CHECK CONSTRAINT ALL');

        $industries = [
            [
                'code' => 'ID01',
                'name' => 'AUTOMOTIVE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID02',
                'name' => 'BUILDING MATERIAL',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID03',
                'name' => 'CERAMIC TILE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID04',
                'name' => 'CHEMICAL',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID05',
                'name' => 'ELECTRONIC',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID06',
                'name' => 'FOOD & BEVERAGES',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID07',
                'name' => 'FARM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID08',
                'name' => 'PHARMACY',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID09',
                'name' => 'FURNITURE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID10',
                'name' => 'GARMENT',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID11',
                'name' => 'HOUSEWARE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID12',
                'name' => 'INDUSTRY',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID13',
                'name' => 'PAPER',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID14',
                'name' => 'PERSONAL CARES',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID15',
                'name' => 'SHOES',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID16',
                'name' => 'TEXTILE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID17',
                'name' => 'TOYS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ID18',
                'name' => 'OTHER INDUSTRY',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert data using query builder instead of Eloquent
        DB::table('industries')->insert($industries);
    }
}
