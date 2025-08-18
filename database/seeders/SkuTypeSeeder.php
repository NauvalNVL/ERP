<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkuType;

class SkuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skuTypes = [
            [
                'code' => 'NS',
                'name' => 'Non-Stock',
                'description' => 'Non-stock items that are not physically stored',
                'is_active' => true
            ],
            [
                'code' => 'SI',
                'name' => 'Stock Item',
                'description' => 'Stock items that are physically stored and tracked',
                'is_active' => true
            ],
            [
                'code' => 'RM',
                'name' => 'Raw Material',
                'description' => 'Raw materials used in production',
                'is_active' => true
            ],
            [
                'code' => 'FG',
                'name' => 'Finished Goods',
                'description' => 'Finished goods ready for sale',
                'is_active' => true
            ],
            [
                'code' => 'WIP',
                'name' => 'Work in Progress',
                'description' => 'Items currently in production',
                'is_active' => true
            ]
        ];

        foreach ($skuTypes as $skuType) {
            SkuType::updateOrCreate(
                ['code' => $skuType['code']],
                $skuType
            );
        }

        $this->command->info('SKU Types seeded successfully!');
    }
}
