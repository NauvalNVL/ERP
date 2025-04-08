<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ColorGroup;
use Illuminate\Support\Facades\DB;

class ColorGroupSeeder extends Seeder
{
    public function run()
    {
        DB::table('color_groups')->truncate(); // Bersihkan tabel sebelum seeding

        $colorGroups = [
            ['cg' => '01', 'cg_name' => 'BLACK', 'cg_type' => 'X-Flex'],
            ['cg' => '02', 'cg_name' => 'WHITE', 'cg_type' => 'X-Flex'],
            ['cg' => '03', 'cg_name' => 'RED', 'cg_type' => 'X-Flex'],
            ['cg' => '04', 'cg_name' => 'BLUE', 'cg_type' => 'X-Flex'],
            ['cg' => '05', 'cg_name' => 'GREEN', 'cg_type' => 'X-Flex'],
            ['cg' => '06', 'cg_name' => 'YELLOW', 'cg_type' => 'X-Flex'],
            ['cg' => '07', 'cg_name' => 'MAGENTA', 'cg_type' => 'X-Flex'],
            ['cg' => '08', 'cg_name' => 'CYAN', 'cg_type' => 'X-Flex'],
            ['cg' => '09', 'cg_name' => 'ORANGE', 'cg_type' => 'C-Coating'],
            ['cg' => '10', 'cg_name' => 'BROWN', 'cg_type' => 'S-Offset'],
            ['cg' => '11', 'cg_name' => 'GRAY', 'cg_type' => 'S-Offset'],
            ['cg' => '12', 'cg_name' => 'PURPLE', 'cg_type' => 'C-Coating'],
            ['cg' => '13', 'cg_name' => 'VIOLET', 'cg_type' => 'S-Offset'],
            ['cg' => '14', 'cg_name' => 'CREAM', 'cg_type' => 'S-Offset'],
            ['cg' => '15', 'cg_name' => 'PANTONE', 'cg_type' => 'S-Offset']
        ];

        foreach ($colorGroups as $group) {
            ColorGroup::create($group);
        }
    }
} 