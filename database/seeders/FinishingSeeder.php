<?php

namespace Database\Seeders;

use App\Models\Finishing;
use Illuminate\Database\Seeder;

class FinishingSeeder extends Seeder
{
    public function run()
    {
        $finishings = [
            ['code' => '001', 'description' => 'GLUE'],
            ['code' => '002', 'description' => 'STITCH'],
            ['code' => '003', 'description' => 'ASSEMBLY'],
            ['code' => '004', 'description' => 'HEAT'],
            ['code' => '005', 'description' => 'STRIPPING'],
            ['code' => '006', 'description' => 'WRAPPING'],
            ['code' => '007', 'description' => 'PALLET'],
        ];

        foreach ($finishings as $finishing) {
            Finishing::updateOrCreate(
                ['code' => $finishing['code']],
                array_merge($finishing, ['status' => 'Act'])
            );
        }
    }
}
