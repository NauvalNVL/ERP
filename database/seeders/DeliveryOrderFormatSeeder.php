<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryOrderFormat;

class DeliveryOrderFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formats = [
            ['code' => 'A', 'name' => 'DO FORMAT A', 'type' => 'M', 'printer' => 'PRINTER19'],
            ['code' => 'K', 'name' => 'KIM DO Format', 'type' => 'M', 'printer' => 'VIEWER'],
            ['code' => 'L', 'name' => 'MBI Format with KIM Customer Address', 'type' => 'M', 'printer' => 'VIEWER'],
            ['code' => 'M', 'name' => 'MBI Format with Multi Kemasindo Addr', 'type' => 'M', 'printer' => 'VIEWER'],
            ['code' => 'X', 'name' => 'BOX Diecut B - Bilik', 'type' => 'M', 'printer' => 'VIEWER'],
            ['code' => 'Y', 'name' => 'BOX Diecut K - Kotak', 'type' => 'M', 'printer' => 'VIEWER'],
        ];

        foreach ($formats as $format) {
            DeliveryOrderFormat::updateOrCreate(
                ['code' => $format['code']],
                $format
            );
        }
    }
}
