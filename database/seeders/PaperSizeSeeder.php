<?php

namespace Database\Seeders;

use App\Models\PaperSize;
use Illuminate\Database\Seeder;

class PaperSizeSeeder extends Seeder
{
    public function run()
    {
        $paperSizes = [
            ['size' => 1, 'inches' => 0.04, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2, 'inches' => 0.08, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 3, 'inches' => 0.12, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 4, 'inches' => 0.16, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 5, 'inches' => 0.20, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 6, 'inches' => 0.24, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 7, 'inches' => 0.28, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 8, 'inches' => 0.31, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 9, 'inches' => 0.35, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 10, 'inches' => 0.39, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 11, 'inches' => 0.43, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 12, 'inches' => 0.47, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 13, 'inches' => 0.51, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 14, 'inches' => 0.55, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 15, 'inches' => 0.59, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 16, 'inches' => 0.63, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 17, 'inches' => 0.67, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 18, 'inches' => 0.71, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 19, 'inches' => 0.75, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 20, 'inches' => 0.79, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 21, 'inches' => 0.83, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 22, 'inches' => 0.87, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 23, 'inches' => 0.91, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 24, 'inches' => 0.94, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 25, 'inches' => 0.98, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            // Nilai-nilai lain dari gambar
            ['size' => 500, 'inches' => 19.69, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 600, 'inches' => 23.62, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 700, 'inches' => 27.56, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 800, 'inches' => 31.50, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 900, 'inches' => 35.43, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1000, 'inches' => 39.37, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1100, 'inches' => 43.31, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1200, 'inches' => 47.24, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1300, 'inches' => 51.18, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1400, 'inches' => 55.12, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1500, 'inches' => 59.06, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1600, 'inches' => 62.99, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1700, 'inches' => 66.93, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1800, 'inches' => 70.87, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 1900, 'inches' => 74.80, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2000, 'inches' => 78.74, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2100, 'inches' => 82.68, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2200, 'inches' => 86.61, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2300, 'inches' => 90.55, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2400, 'inches' => 94.49, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
            ['size' => 2500, 'inches' => 98.43, 'unit' => 'Millimeter', 'created_by' => 'system', 'updated_by' => 'system'],
        ];

        foreach ($paperSizes as $paperSize) {
            PaperSize::create($paperSize);
        }
    }
} 