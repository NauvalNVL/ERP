<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductGroup;

class ProductGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productGroups = [
            [
                'product_group_id' => 'B',
                'product_group_name' => 'BOX',
                'is_active' => true
            ],
            [
                'product_group_id' => 'OF',
                'product_group_name' => 'OFFSET',
                'is_active' => true
            ],
            [
                'product_group_id' => 'OT',
                'product_group_name' => 'OTHER',
                'is_active' => true
            ],
            [
                'product_group_id' => 'R',
                'product_group_name' => 'PAPER ROLL',
                'is_active' => true
            ],
            [
                'product_group_id' => 'S',
                'product_group_name' => 'SHEET BOARD',
                'is_active' => true
            ],
        ];

        foreach ($productGroups as $groupData) {
            ProductGroup::updateOrCreate(
                ['product_group_id' => $groupData['product_group_id']],
                $groupData
            );
        }
    }
}
