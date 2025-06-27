<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MmTaxType;

class MmTaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initial tax types data
        $taxTypes = [
            [
                'code' => 'NON',
                'name' => 'NON PPN',
                'is_applied' => false,
                'rate' => 0.00
            ],
            [
                'code' => 'NPH21J',
                'name' => 'PPH21 JASA 2.5%',
                'is_applied' => true,
                'rate' => 2.50
            ],
            [
                'code' => 'PH22P1',
                'name' => 'PPH22 (1.5%)',
                'is_applied' => true,
                'rate' => 1.50
            ],
            [
                'code' => 'PN2025',
                'name' => 'PPN',
                'is_applied' => true,
                'rate' => 11.00
            ],
            [
                'code' => 'PPH05J',
                'name' => 'PPH JASA 0.5%',
                'is_applied' => true,
                'rate' => 0.50
            ],
            [
                'code' => 'PPH21',
                'name' => 'PPH21 (2.5%)',
                'is_applied' => true,
                'rate' => 2.50
            ],
            [
                'code' => 'PPH21N',
                'name' => 'PPH21 (3%) NON NPWP',
                'is_applied' => true,
                'rate' => 3.00
            ],
            [
                'code' => 'PPH22',
                'name' => 'PPH22 (0.3%)',
                'is_applied' => true,
                'rate' => 0.30
            ],
            [
                'code' => 'PPH22P',
                'name' => 'PPH22 (0.25%)',
                'is_applied' => true,
                'rate' => 0.25
            ],
            [
                'code' => 'PPH2J',
                'name' => 'PPH23 JASA (2%)',
                'is_applied' => true,
                'rate' => 2.00
            ],
            [
                'code' => 'PPH2S',
                'name' => 'PPH23 SEWA (2%)',
                'is_applied' => true,
                'rate' => 2.00
            ],
            [
                'code' => 'PPH42',
                'name' => 'PPH PASAL 4 AYAT 2 (10%)',
                'is_applied' => true,
                'rate' => 10.00
            ],
            [
                'code' => 'PPH4J',
                'name' => 'PPH23 JASA (4%)',
                'is_applied' => true,
                'rate' => 4.00
            ],
            [
                'code' => 'PPH4S',
                'name' => 'PPH23 SEWA (4%)',
                'is_applied' => true,
                'rate' => 4.00
            ],
            [
                'code' => 'PPN',
                'name' => 'PPN 10%',
                'is_applied' => true,
                'rate' => 10.00
            ],
            [
                'code' => 'PPN11',
                'name' => 'PPN 11%',
                'is_applied' => true,
                'rate' => 11.00
            ]
        ];

        // Insert or update tax types
        foreach ($taxTypes as $taxType) {
            MmTaxType::updateOrCreate(
                ['code' => $taxType['code']],
                $taxType
            );
        }
    }
} 