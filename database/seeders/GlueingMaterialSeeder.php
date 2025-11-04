<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GlueingMaterial;

class GlueingMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $glueingMaterials = [
            [
                'code' => '001',
                'name' => 'PVAC',
                'description' => 'Polyvinyl Acetate Glue',
                'is_active' => true,
            ],
            [
                'code' => '002',
                'name' => 'STARCH',
                'description' => 'Starch Based Glue',
                'is_active' => true,
            ],
            [
                'code' => '003',
                'name' => 'HOT MELT',
                'description' => 'Hot Melt Adhesive',
                'is_active' => true,
            ],
            [
                'code' => '004',
                'name' => 'SILICATE',
                'description' => 'Sodium Silicate Glue',
                'is_active' => true,
            ],
        ];

        foreach ($glueingMaterials as $material) {
            GlueingMaterial::updateOrCreate(
                ['code' => $material['code']],
                $material
            );
        }
    }
}
