<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if target table does not exist in this environment
        if (!Schema::hasTable('colors')) {
            return;
        }

        // Hapus data yang sudah ada untuk menghindari duplikasi
        try {
            DB::table('colors')->truncate();
        } catch (QueryException $e) {
            // If truncate fails due to constraints, fallback to delete
            DB::table('colors')->delete();
        }

        $colors = [
            [
                'color_id' => '00001',
                'color_name' => 'Black',
                'origin' => '01',
                'color_group_id' => '01',
                'cg_type' => 'X-Flexo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00002',
                'color_name' => 'White',
                'origin' => '02',
                'color_group_id' => '02',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00003',
                'color_name' => 'Red',
                'origin' => '03',
                'color_group_id' => '03',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00004',
                'color_name' => 'Dark Red',
                'origin' => '03',
                'color_group_id' => '03',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00005',
                'color_name' => 'Light Red',
                'origin' => '03',
                'color_group_id' => '03',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00006',
                'color_name' => 'Blue',
                'origin' => '04',
                'color_group_id' => '04',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00007',
                'color_name' => 'Dark Blue',
                'origin' => '04',
                'color_group_id' => '04',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00008',
                'color_name' => 'Light Blue',
                'origin' => '04',
                'color_group_id' => '04',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00009',
                'color_name' => 'Green',
                'origin' => '05',
                'color_group_id' => '05',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00010',
                'color_name' => 'Dark Green',
                'origin' => '05',
                'color_group_id' => '05',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00011',
                'color_name' => 'Light Green',
                'origin' => '05',
                'color_group_id' => '05',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00012',
                'color_name' => 'Cyan',
                'origin' => '06',
                'color_group_id' => '06',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00013',
                'color_name' => 'Dark Cyan',
                'origin' => '06',
                'color_group_id' => '06',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00014',
                'color_name' => 'Light Cyan',
                'origin' => '06',
                'color_group_id' => '06',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00015',
                'color_name' => 'Magenta',
                'origin' => '07',
                'color_group_id' => '07',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00016',
                'color_name' => 'Dark Magenta',
                'origin' => '07',
                'color_group_id' => '07',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00017',
                'color_name' => 'Light Magenta',
                'origin' => '07',
                'color_group_id' => '07',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00018',
                'color_name' => 'Pink',
                'origin' => '08',
                'color_group_id' => '08',
                'cg_type' => 'X-Flexo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00019',
                'color_name' => 'Dark Pink',
                'origin' => '08',
                'color_group_id' => '08',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00020',
                'color_name' => 'Light Pink',
                'origin' => '08',
                'color_group_id' => '08',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        try {
            foreach ($colors as $color) {
                DB::table('colors')->insert($color);
            }
            $this->command->info('Data warna berhasil dimasukkan.');
        } catch (QueryException $e) {
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
}