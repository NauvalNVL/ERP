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
        [
            'Code' => 'S111',
            'Name' => 'SLM 111',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI006',
            'TargetSales' => 1300000.00,
            'Internal' => 'SLS',
            'Email' => 'slm111@company.com',
            'status' => 'Active',
        ],
        [
            'Code' => 'S118',
            'Name' => 'SLM 118',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI007',
            'TargetSales' => 1400000.00,
            'Internal' => 'SLS',
            'Email' => 'slm118@company.com',
            'status' => 'Active',
        ],
        [
            'Code' => 'S140',
            'Name' => 'SLM 140',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI008',
            'TargetSales' => 1500000.00,
            'Internal' => 'SLS',
            'Email' => 'slm140@company.com',
            'status' => 'Active',
        ],
        [
            'Code' => 'S142',
            'Name' => 'SLM 142',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI009',
            'TargetSales' => 1600000.00,
            'Internal' => 'SLS',
            'Email' => 'slm142@company.com',
            'status' => 'Active',
        ],
        [
            'Code' => 'S143',
            'Name' => 'SLM 143',
            'Grup' => 'MBI',
            'CodeGrup' => 'MBI010',
            'TargetSales' => 1700000.00,
            'Internal' => 'SLS',
            'Email' => 'slm143@company.com',
            'status' => 'Active',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding (cannot TRUNCATE because of FK references)
        DB::table('salesperson')->delete();

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
