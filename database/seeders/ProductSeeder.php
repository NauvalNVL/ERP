<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'product_code' => '001',
                'description' => 'BOX',
                'product_group_id' => 'B',
                'category' => '1-Corrugated Carton Box',
                'is_active' => true
            ],
            [
                'product_code' => '002',
                'description' => 'OFFSET PRINTING',
                'product_group_id' => 'OF',
                'category' => '7-Other Packaging Products',
                'is_active' => true
            ],
            [
                'product_code' => '003',
                'description' => 'PAPER ROLL STANDARD',
                'product_group_id' => 'R',
                'category' => '2-Single Facer Roll',
                'is_active' => true
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
