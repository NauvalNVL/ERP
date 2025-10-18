<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxRates = [
            [
                'CODE' => '01',
                'NAME' => 'PPN 11%',
                'TAXCODE' => 'PPN11',
                'TAXNAME' => 'PPN 11%',
                'RATEPPH' => 0,
                'RATEPPN' => 11.00,
            ],
            [
                'CODE' => '02',
                'NAME' => 'PPN 11% + PPh 2%',
                'TAXCODE' => 'PPN11',
                'TAXNAME' => 'PPN 11%',
                'RATEPPH' => 2.00,
                'RATEPPN' => 11.00,
            ],
            [
                'CODE' => '03',
                'NAME' => 'Non PPN',
                'TAXCODE' => 'NONPPN',
                'TAXNAME' => 'Non PPN',
                'RATEPPH' => 0,
                'RATEPPN' => 0,
            ],
        ];

        foreach ($taxRates as $taxRate) {
            DB::table('taxrate')->updateOrInsert(
                ['CODE' => $taxRate['CODE']],
                $taxRate
            );
        }

        $this->command->info('Tax rates seeded successfully!');
    }
}
