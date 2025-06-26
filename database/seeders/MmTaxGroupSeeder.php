<?php

namespace Database\Seeders;

use App\Models\MmTaxGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MmTaxGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxGroups = [
            ['code' => 'NON', 'name' => 'NON PPN'],
            ['code' => 'STD', 'name' => 'STANDARD TAX'],
            ['code' => 'VAT10', 'name' => 'VAT 10%'],
            ['code' => 'VAT11', 'name' => 'VAT 11%'],
            ['code' => 'PPN21', 'name' => 'PPN 2.5% + PPN 10%'],
            ['code' => 'PPH21S', 'name' => 'PPH SEWA 2.5% + PPN 10%'],
            ['code' => 'PN2025', 'name' => 'PPN'],
        ];

        foreach ($taxGroups as $taxGroup) {
            MmTaxGroup::updateOrCreate(
                ['code' => $taxGroup['code']],
                $taxGroup
            );
        }
    }
} 