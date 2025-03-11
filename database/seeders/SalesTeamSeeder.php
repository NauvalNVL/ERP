<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'code' => '01',
                'name' => 'MBI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '02',
                'name' => 'MANAGEMENT LOCAL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '03',
                'name' => 'MANAGEMENT MNC',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('sales_team')->insert($teams);
    }
} 