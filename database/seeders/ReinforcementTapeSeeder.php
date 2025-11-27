<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReinforcementTape;

class ReinforcementTapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reinforcementTapes = [
            [
                'code' => '001',
                'name' => 'LAKBAN SERAT',
                'dry_end_code' => '1',
                'status' => 'Act',
                'is_active' => true,
            ],
            [
                'code' => '002',
                'name' => 'REINFORCEMENT TAPE TYPE A',
                'dry_end_code' => '2',
                'status' => 'Act',
                'is_active' => true,
            ],
            [
                'code' => '003',
                'name' => 'REINFORCEMENT TAPE TYPE B',
                'dry_end_code' => '3',
                'status' => 'Act',
                'is_active' => true,
            ],
            [
                'code' => '004',
                'name' => 'REINFORCEMENT TAPE TYPE C',
                'dry_end_code' => '4',
                'status' => 'Act',
                'is_active' => true,
            ],
        ];

        foreach ($reinforcementTapes as $tape) {
            ReinforcementTape::updateOrCreate(
                ['code' => $tape['code']],
                $tape
            );
        }
    }
}
