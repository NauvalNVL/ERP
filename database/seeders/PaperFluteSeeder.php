<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaperFlute;

class PaperFluteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flutes = [
            ['No' => 1, 'Flute' => 'AB', 'Descr' => 'AB', 'DB' => 1.00, 'B' => 1.40, '_1L' => 1.00, 'A_C_E' => 1.60, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 2, 'Flute' => 'AF', 'Descr' => 'A FLUTE', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.50, '_2L' => 1.00, 'Height' => 4.80, 'Starch' => 6.00],
            ['No' => 3, 'Flute' => 'BC', 'Descr' => 'BC', 'DB' => 1.00, 'B' => 1.40, '_1L' => 1.00, 'A_C_E' => 1.40, '_2L' => 1.00, 'Height' => 5.00, 'Starch' => 18.00],
            ['No' => 4, 'Flute' => 'BC2', 'Descr' => 'BC 2 FLUTE', 'DB' => 1.00, 'B' => 1.35, '_1L' => 1.00, 'A_C_E' => 1.35, '_2L' => 1.00, 'Height' => 5.50, 'Starch' => 14.00],
            ['No' => 5, 'Flute' => 'BC3', 'Descr' => 'B FLUTE', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.40, '_2L' => 1.00, 'Height' => 2.50, 'Starch' => 10.00],
            ['No' => 6, 'Flute' => 'BFS', 'Descr' => 'BF SINGLE FACER', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.40, '_2L' => 1.00, 'Height' => 2.50, 'Starch' => 10.00],
            ['No' => 7, 'Flute' => 'BR', 'Descr' => 'BHPT ROLL', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 8, 'Flute' => 'C', 'Descr' => 'C', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 9, 'Flute' => 'CF2', 'Descr' => 'C FLUTE', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.40, '_2L' => 1.00, 'Height' => 3.60, 'Starch' => 11.00],
            ['No' => 10, 'Flute' => 'CN', 'Descr' => 'CHINESE', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 11, 'Flute' => 'EB', 'Descr' => 'EB', 'DB' => 1.00, 'B' => 1.40, '_1L' => 1.00, 'A_C_E' => 1.30, '_2L' => 1.00, 'Height' => 3.20, 'Starch' => 15.00],
            ['No' => 12, 'Flute' => 'EC', 'Descr' => 'EC', 'DB' => 1.00, 'B' => 1.40, '_1L' => 1.00, 'A_C_E' => 1.30, '_2L' => 1.00, 'Height' => 5.00, 'Starch' => 16.00],
            ['No' => 13, 'Flute' => 'EF5', 'Descr' => 'EF SINGLE FACER', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.30, '_2L' => 1.00, 'Height' => 1.20, 'Starch' => 6.00],
            ['No' => 14, 'Flute' => 'FS', 'Descr' => 'F5 SINGLE FACER', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 15, 'Flute' => 'GLV', 'Descr' => 'LAIN LAIN', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 16, 'Flute' => 'MC', 'Descr' => 'OFFSET SINGLE DMNX', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 17, 'Flute' => 'OF', 'Descr' => 'OFFSET DOUBLE DMNX', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
            ['No' => 18, 'Flute' => 'OFD', 'Descr' => 'OFFSET DOUBLE DUPLEX', 'DB' => 1.00, 'B' => 1.00, '_1L' => 1.00, 'A_C_E' => 1.00, '_2L' => 1.00, 'Height' => 0.00, 'Starch' => 0.00],
        ];

        foreach ($flutes as $flute) {
            PaperFlute::updateOrCreate(
                ['Flute' => $flute['Flute']],
                $flute
            );
        }
    }
}
