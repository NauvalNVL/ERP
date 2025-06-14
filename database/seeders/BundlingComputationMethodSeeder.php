<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BundlingComputationMethod;

class BundlingComputationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample data
        $sampleData = [
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'AB',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'AC',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'AF',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'BC',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'BC2',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'BE',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'BF',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'BF5',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'CF',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'CF2',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'CN',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'EB',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
            [
                'product_design' => 'APFI',
                'product' => 'BKS',
                'flute' => 'EC',
                'formula_pieces' => 0,
                'is_compute' => false,
                'formula_divisor' => 1,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'created_by' => 'System',
            ],
        ];

        foreach ($sampleData as $data) {
            BundlingComputationMethod::updateOrCreate(
                [
                    'product_design' => $data['product_design'],
                    'product' => $data['product'],
                    'flute' => $data['flute'],
                ],
                $data
            );
        }
    }
} 