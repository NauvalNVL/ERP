<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductDesign;

class ProductDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productDesigns = [
            [
                'pd_code' => 'PD001',
                'pd_name' => 'Basic Square Box',
                'product_code' => 'BOX001',
                'dimension' => '100x100x100',
                'idc' => 'BSQ-100',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD002',
                'pd_name' => 'Medium Rectangle Box',
                'product_code' => 'BOX002',
                'dimension' => '200x150x100',
                'idc' => 'MRB-200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD003',
                'pd_name' => 'Large Storage Box',
                'product_code' => 'BOX003',
                'dimension' => '300x200x150',
                'idc' => 'LSB-300',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD004',
                'pd_name' => 'Slim Document Case',
                'product_code' => 'DOC001',
                'dimension' => '330x240x30',
                'idc' => 'SDC-330',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD005',
                'pd_name' => 'Folder Design',
                'product_code' => 'FOL001',
                'dimension' => '320x230x5',
                'idc' => 'FD-320',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD006',
                'pd_name' => 'Small Gift Box',
                'product_code' => 'GFT001',
                'dimension' => '100x100x50',
                'idc' => 'SGB-100',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD007',
                'pd_name' => 'Premium Gift Box',
                'product_code' => 'GFT002',
                'dimension' => '200x200x100',
                'idc' => 'PGB-200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD008',
                'pd_name' => 'Envelope Standard',
                'product_code' => 'ENV001',
                'dimension' => '220x110x0',
                'idc' => 'ES-220',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD009',
                'pd_name' => 'Business Card',
                'product_code' => 'BC001',
                'dimension' => '90x55x0',
                'idc' => 'BC-90',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'PD010',
                'pd_name' => 'A4 Notebook Design',
                'product_code' => 'NB001',
                'dimension' => '297x210x15',
                'idc' => 'A4N-297',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert the product designs into the database
        foreach ($productDesigns as $design) {
            ProductDesign::create($design);
        }
    }
} 