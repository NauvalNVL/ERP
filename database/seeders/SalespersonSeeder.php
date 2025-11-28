<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalespersonSeeder extends Seeder
{
    /**
     * The salespersons data with new structure.
     */
    protected $salespersons = [
        [
            'Code' => 'S101',
            'Name' => 'ABENG',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI001',
            'TargetSales' => 1000000.00,
            'Internal' => 'root',
            'Email' => 'abeng@company.com',
            'status' => 'Active',
        ],
        [
            'Code' => 'S102',
            'Name' => 'AGUNG',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI002',
            'TargetSales' => 1200000.00,
            'Internal' => 'SLS',
            'Email' => 'agung@company.com',
            'status' => 'Inactive',
        ],
        [
            'Code' => 'S103',
            'Name' => 'EKO',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI003',
            'TargetSales' => 900000.00,
            'Internal' => 'SLS',
            'Email' => 'eko@company.com',
            'status' => 'Active',
        ],
        [
            'Code' => 'S104',
            'Name' => 'ELIAS',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI004',
            'TargetSales' => 1100000.00,
            'Internal' => 'SLS',
            'Email' => 'elias@company.com',
            'status' => 'Inactive',
        ],
        [
            'Code' => 'S105',
            'Name' => 'FEBBY',
            'Grup' => 'MANAGEMENT LOCAL',
            'CodeGrup' => 'ML001',
            'TargetSales' => 1500000.00,
            'Internal' => 'SLS',
            'Email' => 'febby@company.com',
            'status' => 'Active',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding
        DB::table('salesperson')->truncate();

        // Insert data without timestamps (table doesn't have timestamp columns)
        DB::table('salesperson')->insert($this->salespersons);
    }

    /**
     * Get the seeder data.
     */
    public function getSeederData()
    {
        return $this->salespersons;
    }
}
