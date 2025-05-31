<?php

namespace Database\Seeders;

use App\Models\PaperFlute;
use Illuminate\Database\Seeder;

class PaperFluteSeeder extends Seeder
{
    protected $flutes = [
            [
                'code' => 'tes',
                'name' => 'tes',
                'description' => 'tes',
                'tur_l2b' => 1,
                'tur_l3' => 1.4,
                'tur_l1' => 1,
                'tur_ace' => 1.5,
                'tur_2l' => 1,
                'flute_height' => 0.01,
                'starch_consumption' => 0,
                'is_active' => true,
            ],
        [
            'code' => 'AB',
            'name' => 'AB',
            'description' => 'AB',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.40,
            'tur_l1' => 1.00,
            'tur_ace' => 1.60,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'AC',
            'name' => 'AC',
            'description' => 'A FLUTE',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.40,
            'tur_l1' => 1.00,
            'tur_ace' => 1.55,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'BC',
            'name' => 'BC',
            'description' => 'BC 3 LAYER',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.40,
            'tur_l1' => 1.00,
            'tur_ace' => 1.55,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'BF',
            'name' => 'BF',
            'description' => 'BF',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.40,
            'tur_l1' => 1.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'BFF',
            'name' => 'BFF',
            'description' => 'BF SINGLE FACER',
            'tur_l2b' => 0.00,
            'tur_l3' => 1.40,
            'tur_l1' => 1.00,
            'tur_ace' => 0.00,
            'tur_2l' => 0.00,
            'flute_height' => 0.00,
            'starch_consumption' => 1.00
        ],
        [
            'code' => 'BUFF',
            'name' => 'BUFF',
            'description' => 'BUFF ROLL',
            'tur_l2b' => 1.00,
            'tur_l3' => 0.00,
            'tur_l1' => 1.00,
            'tur_ace' => 0.00,
            'tur_2l' => 0.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'C',
            'name' => 'C',
            'description' => 'C',
            'tur_l2b' => 1.00,
            'tur_l3' => 0.00,
            'tur_l1' => 0.00,
            'tur_ace' => 1.55,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'CF2',
            'name' => 'CF2',
            'description' => 'C FLUTE 2',
            'tur_l2b' => 1.00,
            'tur_l3' => 0.00,
            'tur_l1' => 0.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'DBREST',
            'name' => 'DBREST',
            'description' => 'DBREST',
            'tur_l2b' => 1.00,
            'tur_l3' => 0.00,
            'tur_l1' => 1.00,
            'tur_ace' => 0.00,
            'tur_2l' => 0.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'E',
            'name' => 'E',
            'description' => 'E',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.30,
            'tur_l1' => 1.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'EF',
            'name' => 'EF',
            'description' => 'E FLUTE',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.30,
            'tur_l1' => 1.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'EFF',
            'name' => 'EFF',
            'description' => 'EF SINGLE FACER',
            'tur_l2b' => 0.00,
            'tur_l3' => 0.00,
            'tur_l1' => 0.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 1.00
        ],
        [
            'code' => 'KL',
            'name' => 'KL',
            'description' => 'K LINER SINGLE FACER',
            'tur_l2b' => 0.00,
            'tur_l3' => 0.00,
            'tur_l1' => 0.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'ETC',
            'name' => 'ETC',
            'description' => 'LAIN-LAIN',
            'tur_l2b' => 0.00,
            'tur_l3' => 0.00,
            'tur_l1' => 0.00,
            'tur_ace' => 1.50,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'OD',
            'name' => 'OD',
            'description' => 'OFFSET RF',
            'tur_l2b' => 1.00,
            'tur_l3' => 0.00,
            'tur_l1' => 0.00,
            'tur_ace' => 1.00,
            'tur_2l' => 1.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'OS',
            'name' => 'OS',
            'description' => 'OFFSET ROLL',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.00,
            'tur_l1' => 0.00,
            'tur_ace' => 0.00,
            'tur_2l' => 0.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'OFD',
            'name' => 'OFD',
            'description' => 'OFFSET DOUBLE PAPER',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.00,
            'tur_l1' => 0.00,
            'tur_ace' => 0.00,
            'tur_2l' => 0.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
        [
            'code' => 'RFP',
            'name' => 'RFP',
            'description' => 'OFFSET 2 PAPER',
            'tur_l2b' => 1.00,
            'tur_l3' => 1.00,
            'tur_l1' => 0.00,
            'tur_ace' => 0.00,
            'tur_2l' => 0.00,
            'flute_height' => 0.00,
            'starch_consumption' => 0.00
        ],
                                [
                'code' => '2L',
                'name' => '2L',
                'description' => 'ROLL',
                'tur_l2b' => 1.00,
                'tur_l3' => 1.40,
                'tur_l1' => 1.00,
                'tur_ace' => 1.50,
                'tur_2l' => 1.00,
                'flute_height' => 0,
                'starch_consumption' => .00,
                'is_active' => false,
            ],,
    ];

    public function run(): void
    {
        foreach ($this->flutes as $fluteData) {
            PaperFlute::updateOrCreate(
                ['code' => $fluteData['code']],
                $fluteData
            );
        }
    }

    /**
     * Get the seeder data.
     */
    public function getSeederData()
    {
        return $this->flutes;
    }
}
