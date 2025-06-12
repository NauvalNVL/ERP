<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a default product group
        $defaultGroup = ProductGroup::firstOrCreate(
            ['product_group_id' => 'DEFAULT'],
            [
                'product_group_name' => 'Default Product Group',
                'is_active' => true
            ]
        );

        // Define products based on the image
        $products = [
            ['code' => '001', 'name' => 'RSC STANDARD'],
            ['code' => '002', 'name' => 'DIE CUT'],
            ['code' => '003', 'name' => 'BHPT BOX'],
            ['code' => '004', 'name' => 'PENJUALAN WASTE'],
            ['code' => '005', 'name' => 'PENJUALAN LAIN-LAIN PCS'],
            ['code' => '006', 'name' => 'CONEJIT'],
            ['code' => '007', 'name' => 'ROLL'],
            ['code' => '008', 'name' => 'PENJUALAN LAIN-LAIN KG'],
            ['code' => '009', 'name' => 'PENJUALAN LAIN-LAIN'],
            ['code' => '010', 'name' => 'TRAY'],
            ['code' => '011', 'name' => 'SINGLE FACER KG'],
            ['code' => '012', 'name' => 'SINGLE FACER SHEET'],
            ['code' => '013', 'name' => 'PENJUALAN LAIN-LAIN'],
            ['code' => '014', 'name' => 'SEWA TRUCK'],
            ['code' => '015', 'name' => 'CORE PLUS'],
            ['code' => '016', 'name' => 'PAPER TUBE'],
            ['code' => '017', 'name' => 'OFFSET'],
            ['code' => '018', 'name' => '2 FAX OFFSET'],
            ['code' => '019', 'name' => 'DIGITAL PRINT'],
            ['code' => '020', 'name' => 'SEWA TRUCK TRAILER'],
        ];

        // Create or update products
        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['product_code' => $productData['code']],
                [
                    'product_code' => $productData['code'],
                    'description' => $productData['name'],
                    'product_group_id' => $defaultGroup->product_group_id,
                    'category' => 'STANDARD',
                    'is_active' => true
                ]
            );
        }

        $this->command->info('Products seeded successfully!');
    }
}
