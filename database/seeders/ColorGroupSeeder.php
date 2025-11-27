<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ColorGroup;
use Illuminate\Support\Facades\DB;

class ColorGroupSeeder extends Seeder
{
    /**
     * Seed the COLOR_GROUP table with sample data
     * Matching CPS Enterprise 2020 structure
     */
    public function run()
    {
        // Sample color groups from CPS Enterprise 2020
        $colorGroups = [
            ['CG' => '01', 'CG_Name' => 'BLACK', 'CG_Type' => 'X-Flexo'],
            ['CG' => '02', 'CG_Name' => 'WHITE', 'CG_Type' => 'X-Flexo'],
            ['CG' => '03', 'CG_Name' => 'RED', 'CG_Type' => 'X-Flexo'],
            ['CG' => '04', 'CG_Name' => 'BLUE', 'CG_Type' => 'X-Flexo'],
            ['CG' => '05', 'CG_Name' => 'GREEN', 'CG_Type' => 'X-Flexo'],
            ['CG' => '06', 'CG_Name' => 'CYAN', 'CG_Type' => 'C-Coating'],
            ['CG' => '07', 'CG_Name' => 'MAGENTA', 'CG_Type' => 'C-Coating'],
            ['CG' => '08', 'CG_Name' => 'YELLOW', 'CG_Type' => 'S-Offset'],
            ['CG' => '09', 'CG_Name' => 'ORANGE', 'CG_Type' => 'X-Flexo'],
            ['CG' => '10', 'CG_Name' => 'YELLOW', 'CG_Type' => 'X-Flexo'],
            ['CG' => '11', 'CG_Name' => 'PINK', 'CG_Type' => 'X-Flexo'],
            ['CG' => '12', 'CG_Name' => 'GOLD', 'CG_Type' => 'X-Flexo'],
            ['CG' => '13', 'CG_Name' => 'GRAY', 'CG_Type' => 'X-Flexo'],
            ['CG' => '14', 'CG_Name' => 'BROWN', 'CG_Type' => 'X-Flexo'],
            ['CG' => '15', 'CG_Name' => 'VARNISH', 'CG_Type' => 'C-Coating'],
            ['CG' => '16', 'CG_Name' => 'VIOLET', 'CG_Type' => 'X-Flexo'],
            ['CG' => '17', 'CG_Name' => 'PURPLE', 'CG_Type' => 'X-Flexo'],
            ['CG' => '18', 'CG_Name' => 'SILVER', 'CG_Type' => 'S-Offset'],
            ['CG' => 'PANTONE', 'CG_Name' => 'PANTONE', 'CG_Type' => 'S-Offset'],
        ];

        foreach ($colorGroups as $group) {
            ColorGroup::updateOrCreate(
                ['CG' => $group['CG']],
                array_merge($group, ['status' => 'Act'])
            );
        }
    }
}