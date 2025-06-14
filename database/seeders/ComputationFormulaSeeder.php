<?php

namespace Database\Seeders;

use App\Models\ComputationFormula;
use Illuminate\Database\Seeder;

class ComputationFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seedData = [
            [
                'board_length_min' => 0,
                'board_length_max' => 999999,
                'board_width_min' => 0,
                'board_width_max' => 999999,
                'dc_sheet_length_min' => 1,
                'dc_sheet_length_max' => 999999,
                'dc_sheet_width_min' => 1,
                'dc_sheet_width_max' => 999999,
                'no_of_up_min' => 1,
                'no_of_up_max' => 99
            ],
            [
                'board_length_min' => 100,
                'board_length_max' => 5000,
                'board_width_min' => 100,
                'board_width_max' => 5000,
                'dc_sheet_length_min' => 100,
                'dc_sheet_length_max' => 5000,
                'dc_sheet_width_min' => 100,
                'dc_sheet_width_max' => 5000,
                'no_of_up_min' => 1,
                'no_of_up_max' => 10
            ]
        ];

        foreach ($seedData as $data) {
            ComputationFormula::updateOrCreate(
                [
                    'board_length_min' => $data['board_length_min'],
                    'board_width_min' => $data['board_width_min'],
                ],
                $data
            );
        }
    }
} 