<?php

namespace Database\Seeders;

use App\Models\Finishing;
use Illuminate\Database\Seeder;

class FinishingSeeder extends Seeder
{
    public function run()
    {
        $finishings = [
            ['code' => '001', 'description' => 'GLUE', 'is_compute' => false],
            ['code' => '002', 'description' => 'STITCH', 'is_compute' => false],
            ['code' => '003', 'description' => 'ASSEMBLY', 'is_compute' => false],
            ['code' => '004', 'description' => 'HEAT', 'is_compute' => false],
            ['code' => '005', 'description' => 'STRIPPING', 'is_compute' => false],
            ['code' => '006', 'description' => 'WRAPPING', 'is_compute' => false],
            ['code' => '007', 'description' => 'PALLET', 'is_compute' => false],
        ];

        foreach ($finishings as $finishing) {
            Finishing::updateOrCreate(
                ['code' => $finishing['code']],
                array_merge($finishing, ['status' => 'Act'])
            );
        }
    }
}
