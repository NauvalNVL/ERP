<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WrappingMaterial;

class WrappingMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wrappingMaterials = [
            [
                'code' => '001',
                'name' => 'PLASTIK',
                'description' => 'Plastic Wrapping Material',
                'status' => 'Act',
                'is_active' => true,
            ],
            [
                'code' => '002',
                'name' => 'KERTAS',
                'description' => 'Paper Wrapping Material',
                'status' => 'Act',
                'is_active' => true,
            ],
            [
                'code' => '003',
                'name' => 'KARTON',
                'description' => 'Carton Wrapping Material',
                'status' => 'Act',
                'is_active' => true,
            ],
            [
                'code' => '004',
                'name' => 'BUBBLE WRAP',
                'description' => 'Bubble Wrap Material',
                'status' => 'Act',
                'is_active' => true,
            ],
        ];

        foreach ($wrappingMaterials as $material) {
            WrappingMaterial::updateOrCreate(
                ['code' => $material['code']],
                $material
            );
        }
    }
}
