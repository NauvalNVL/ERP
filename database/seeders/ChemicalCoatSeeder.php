<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChemicalCoat;

class ChemicalCoatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chemicalCoats = [
            [
                'code' => '001',
                'name' => 'VERNISH',
                'dry_end_code' => '1',
                'is_active' => true
            ],
            [
                'code' => '002',
                'name' => 'WATER BASE COATING',
                'dry_end_code' => '2',
                'is_active' => true
            ],
            [
                'code' => '003',
                'name' => 'GLOSS COAT',
                'dry_end_code' => '3',
                'is_active' => true
            ],
            [
                'code' => '004',
                'name' => 'MATTE COAT',
                'dry_end_code' => '4',
                'is_active' => true
            ],
            [
                'code' => '005',
                'name' => 'UV COATING',
                'dry_end_code' => '5',
                'is_active' => true
            ],
        ];

        foreach ($chemicalCoats as $coat) {
            ChemicalCoat::updateOrCreate(
                ['code' => $coat['code']],
                $coat
            );
        }
    }
}
