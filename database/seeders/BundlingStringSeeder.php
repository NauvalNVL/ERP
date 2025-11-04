<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BundlingString;

class BundlingStringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bundlingStrings = [
            [
                'code' => '001',
                'name' => '5 MM',
                'is_active' => true,
            ],
            [
                'code' => '002',
                'name' => '7 MM',
                'is_active' => true,
            ],
            [
                'code' => '003',
                'name' => '10 MM',
                'is_active' => true,
            ],
            [
                'code' => '004',
                'name' => '12 MM',
                'is_active' => true,
            ],
        ];

        foreach ($bundlingStrings as $string) {
            BundlingString::updateOrCreate(
                ['code' => $string['code']],
                $string
            );
        }
    }
}
