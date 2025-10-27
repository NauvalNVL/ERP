<?php

namespace Database\Seeders;

use App\Models\PaperSize;
use Illuminate\Database\Seeder;

class PaperSizeSeeder extends Seeder
{
    public function run()
    {
        // Data sesuai dengan CPS Enterprise 2020 (dari gambar)
        // Structure: NO, MILLIMETER, INCHES
        $paperSizes = [
            ['millimeter' => 880, 'inches' => 34.65],
            ['millimeter' => 900, 'inches' => 35.43],
            ['millimeter' => 950, 'inches' => 37.40],
            ['millimeter' => 980, 'inches' => 38.58],
            ['millimeter' => 1000, 'inches' => 39.37],
            ['millimeter' => 1050, 'inches' => 41.34],
            ['millimeter' => 1150, 'inches' => 45.28],
            ['millimeter' => 1180, 'inches' => 46.46],
            ['millimeter' => 1200, 'inches' => 47.24],
            ['millimeter' => 1290, 'inches' => 50.79],
            ['millimeter' => 1300, 'inches' => 51.18],
            ['millimeter' => 1360, 'inches' => 53.54],
            ['millimeter' => 1400, 'inches' => 55.12],
            ['millimeter' => 1450, 'inches' => 57.09],
            ['millimeter' => 1500, 'inches' => 59.06],
            ['millimeter' => 1550, 'inches' => 61.02],
            ['millimeter' => 1600, 'inches' => 62.99],
            ['millimeter' => 1650, 'inches' => 64.96],
            ['millimeter' => 1700, 'inches' => 66.93],
            ['millimeter' => 1780, 'inches' => 70.08],
        ];

        foreach ($paperSizes as $paperSize) {
            PaperSize::updateOrCreate(
                ['millimeter' => $paperSize['millimeter']],
                ['inches' => $paperSize['inches']]
            );
        }
    }
} 