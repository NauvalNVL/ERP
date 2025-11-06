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

        // Define products with category and unit based on the UOM table
        $products = [
            ['code' => '001', 'name' => 'RSC STANDARD', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '002', 'name' => 'DIE CUT', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '003', 'name' => 'BHPT BOX', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '004', 'name' => 'PENJUALAN WASTE', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '005', 'name' => 'PENJUALAN LAIN-LAIN PCS', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '006', 'name' => 'CONEJIT', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '007', 'name' => 'ROLL', 'category' => '2-Single Facer Roll', 'unit' => 'R-Roll'],
            ['code' => '008', 'name' => 'PENJUALAN LAIN-LAIN KG', 'category' => '3-Single Facer Roll/KG', 'unit' => 'K-KG'],
            ['code' => '009', 'name' => 'PENJUALAN LAIN-LAIN', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '010', 'name' => 'TRAY', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '011', 'name' => 'SINGLE FACER KG', 'category' => '3-Single Facer Roll/KG', 'unit' => 'K-KG'],
            ['code' => '012', 'name' => 'SINGLE FACER SHEET', 'category' => '4-Single Facer Sheet', 'unit' => 'T-Sheet'],
            ['code' => '013', 'name' => 'PENJUALAN LAIN-LAIN', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '014', 'name' => 'SEWA TRUCK', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '015', 'name' => 'CORE PLUS', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '016', 'name' => 'PAPER TUBE', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
            ['code' => '017', 'name' => 'OFFSET', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '018', 'name' => '2 FAX OFFSET', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '019', 'name' => 'DIGITAL PRINT', 'category' => '1-Corrugated Carton Box', 'unit' => 'P-Piece,S-Set'],
            ['code' => '020', 'name' => 'SEWA TRUCK TRAILER', 'category' => '7-Other Packaging Products', 'unit' => 'P-Piece,S-Set,D-Bundle,L-Pallet,K-KG,B-Box'],
        ];

        // Create or update products
        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['product_code' => $productData['code']],
                [
                    'product_code' => $productData['code'],
                    'description' => $productData['name'],
                    'product_group_id' => $defaultGroup->product_group_id,
                    'category' => $productData['category'],
                    'unit' => $productData['unit'],
                    'is_active' => true
                ]
            );
        }

        $this->command->info('Products seeded successfully!');
    }
}
