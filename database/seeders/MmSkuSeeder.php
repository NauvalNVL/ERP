<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MmSku;
use App\Models\MmCategory;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

class MmSkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get existing categories
        $categories = MmCategory::pluck('code')->toArray();
        
        if (empty($categories)) {
            $this->command->error('No categories found. Please run MmCategorySeeder first.');
            return;
        }
        
        $categoryCode = $categories[0]; // Use the first category as default
        
        $skus = [
            [
                'sku' => '007-SRV-C27',
                'sts' => 'ACT',
                'sku_name' => 'SERVICE CAL 347NO DRIVER TIPE DASA-560 SPEA',
                'category_code' => $categoryCode,
                'type' => 'NS',
                'uom' => 'UNIT',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000055',
                'sts' => 'ACT',
                'sku_name' => 'BOX SLEEVE DAJAN PT.DELTAPACK',
                'category_code' => $categoryCode,
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000055A',
                'sts' => 'ACT',
                'sku_name' => 'BOX SLEEVE LUAR PT.DELTAPACK',
                'category_code' => $categoryCode,
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000055B',
                'sts' => 'ACT',
                'sku_name' => 'BOX SLEEVE DALAM PT.DELTAPACK',
                'category_code' => $categoryCode,
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '000202',
                'sts' => 'ACT',
                'sku_name' => 'BOX OUTER 2X30CM NEW',
                'category_code' => $categoryCode,
                'type' => 'NS',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A01001',
                'sts' => 'ACT',
                'sku_name' => 'ANNELING WIRE 2 MM (KAWAT PRESS BALLER)',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 2675.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A02001',
                'sts' => 'OBS',
                'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'BAG',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => false,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A02002',
                'sts' => 'ACT',
                'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A02003',
                'sts' => 'ACT',
                'sku_name' => 'ARMEX BAKING SODA POWDER (25KG/BAG)',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 100.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A03001',
                'sts' => 'ACT',
                'sku_name' => 'ALUMINIUM CHLOROHYDRANT/BETAGARD 4040 (ACH)',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 3514.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A04001',
                'sts' => 'ACT',
                'sku_name' => 'AVAL BULAT 6CM',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A05001',
                'sts' => 'ACT',
                'sku_name' => 'AD DACK',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-A06001',
                'sts' => 'ACT',
                'sku_name' => 'ANTIFOAM PKG-1800',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 0.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-B01001',
                'sts' => 'ACT',
                'sku_name' => 'BORAK',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'KG',
                'boh' => 3525.000,
                'fpo' => 0.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-B02001',
                'sts' => 'ACT',
                'sku_name' => 'BALOK 8X10X100',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'PCS',
                'boh' => 0.000,
                'fpo' => 200.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ],
            [
                'sku' => '001-B03001',
                'sts' => 'ACT',
                'sku_name' => 'BEDAK POWDER',
                'category_code' => $categoryCode,
                'type' => 'SI',
                'uom' => 'BTL',
                'boh' => 4.000,
                'fpo' => 12.000,
                'rol' => 0.000,
                'total_part' => 0,
                'is_active' => true,
                'min_qty' => 0.00,
                'max_qty' => 0.00
            ]
        ];

        foreach ($skus as $skuData) {
            MmSku::updateOrCreate(
                ['sku' => $skuData['sku']],
                $skuData
            );
        }

        // Add a test SKU for Amend SKU testing
        try {
            DB::table('mm_skus')->insert([
                'sku' => 'TES-233114',
                'sts' => 'ACT',
                'sku_name' => 'Test SKU for Amend',
                'category_code' => 'CAT-001',
                'type' => 'FG',
                'uom' => 'PCS',
                'boh' => 100,
                'fpo' => 0,
                'rol' => 10,
                'total_part' => 1,
                'min_qty' => 1,
                'max_qty' => 1000,
                'additional_name' => 'Test SKU',
                'part_number1' => null,
                'part_number2' => null,
                'part_number3' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Ignore duplicate entry error
        }
    }
} 