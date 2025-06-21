<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\CorrugatorSpecByProduct;
use Illuminate\Support\Facades\DB;

class CorrugatorSpecByProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing specs
        DB::table('corrugator_spec_by_products')->truncate();
        
        // Define specific product data based on the image
        $productSpecs = [
            ['code' => '001', 'name' => 'RSC STANDARD', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '002', 'name' => 'DIE CUT', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '003', 'name' => 'BHPT BOX', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '004', 'name' => 'PENJUALAN WASTE', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '005', 'name' => 'PENJUALAN LAIN-LAIN PCS', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '006', 'name' => 'CONEJIT', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '007', 'name' => 'ROLL', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '008', 'name' => 'PENJUALAN LAIN-LAIN KG', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '009', 'name' => 'PENJUALAN LAIN-LAIN', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '010', 'name' => 'TRAY', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '011', 'name' => 'SINGLE FACER KG', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '012', 'name' => 'SINGLE FACER SHEET', 'compute' => true, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '013', 'name' => 'PENJUALAN LAIN-LAIN', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '014', 'name' => 'SEWA TRUCK', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '015', 'name' => 'CORE PLUS', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '016', 'name' => 'PAPER TUBE', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '017', 'name' => 'OFFSET', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '018', 'name' => '2 FAX OFFSET', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '019', 'name' => 'DIGITAL PRINT', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
            ['code' => '020', 'name' => 'SEWA TRUCK TRAILER', 'compute' => false, 'min_length' => 1, 'max_length' => 99999, 'min_width' => 1, 'max_width' => 99999],
        ];
        
        // Create specifications for each product
        foreach ($productSpecs as $spec) {
            // Find the product by code
            $product = Product::where('product_code', $spec['code'])->first();
            
            // If product exists, create the specification
            if ($product) {
                CorrugatorSpecByProduct::create([
                    'product_code' => $spec['code'],
                    'compute' => $spec['compute'],
                    'min_sheet_length' => $spec['min_length'],
                    'max_sheet_length' => $spec['max_length'],
                    'min_sheet_width' => $spec['min_width'],
                    'max_sheet_width' => $spec['max_width'],
                ]);
            } else {
                // Log if product not found
                $this->command->info("Product with code {$spec['code']} not found.");
            }
        }
        
        $this->command->info('Corrugator specifications by product seeded successfully!');
    }
} 