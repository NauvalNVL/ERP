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
        if (!Schema::hasTable('COLOR')) {
            return;
        }

        // Hapus data yang sudah ada untuk menghindari duplikasi
        try {
            DB::table('COLOR')->truncate();
        } catch (QueryException $e) {
            // If truncate fails due to constraints, fallback to delete
            DB::table('COLOR')->delete();
        }

        $colors = [
            [
                'color_id' => '00001',
                'color_name' => 'Black',
                'color_group_id' => '01',
                'cg_type' => 'X-Flexo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00002',
                'color_name' => 'White',
                'color_group_id' => '02',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00003',
                'color_name' => 'Red',
                'color_group_id' => '03',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00004',
                'color_name' => 'Dark Red',
                'color_group_id' => '03',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00005',
                'color_name' => 'Light Red',
                'color_group_id' => '03',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00006',
                'color_name' => 'Blue',
                'color_group_id' => '04',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00007',
                'color_name' => 'Dark Blue',
                'color_group_id' => '04',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00008',
                'color_name' => 'Light Blue',
                'color_group_id' => '04',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00009',
                'color_name' => 'Green',
                'color_group_id' => '05',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00010',
                'color_name' => 'Dark Green',
                'color_group_id' => '05',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00011',
                'color_name' => 'Light Green',
                'color_group_id' => '05',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00012',
                'color_name' => 'Cyan',
                'color_group_id' => '06',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00013',
                'color_name' => 'Dark Cyan',
                'color_group_id' => '06',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00014',
                'color_name' => 'Light Cyan',
                'color_group_id' => '06',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00015',
                'color_name' => 'Magenta',
                'color_group_id' => '07',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00016',
                'color_name' => 'Dark Magenta',
                'color_group_id' => '07',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00017',
                'color_name' => 'Light Magenta',
                'color_group_id' => '07',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00018',
                'color_name' => 'Pink',
                'color_group_id' => '08',
                'cg_type' => 'X-Flexo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00019',
                'color_name' => 'Dark Pink',
                'color_group_id' => '08',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color_id' => '00020',
                'color_name' => 'Light Pink',
                'color_group_id' => '08',
                'cg_type' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        try {
            foreach ($colors as $color) {
                DB::table('COLOR')->insert([
                    'Color_Code' => $color['color_id'],
                    'Color_Name' => $color['color_name'],
                    'GroupCode'  => $color['color_group_id'],
                    'Group'      => $color['cg_type'] ?? null,
                    'status'     => 'Act'
                ]);
            }
            $this->command->info('Data warna berhasil dimasukkan ke tabel COLOR.');
        } catch (QueryException $e) {
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
}
