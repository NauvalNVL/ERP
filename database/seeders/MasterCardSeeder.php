<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records before seeding
        DB::table('master_cards')->truncate();

        $masterCards = [
            ['seq' => '1609138', 'model' => 'BOX BASO 4.5 KG', 'status' => 'Active'],
            ['seq' => '1609144', 'model' => 'BOX IKAN HARIMAU 4.5 KG', 'status' => 'Active'],
            ['seq' => '1609145', 'model' => 'BOX SRIKAYA 4.5 KG', 'status' => 'Active'],
            ['seq' => '1609162', 'model' => 'BIHUN FANIA 5 KG', 'status' => 'Active'],
            ['seq' => '1609163', 'model' => 'BIHUN IKAN TUNA 4.5 KG BARU', 'status' => 'Active'],
            ['seq' => '1609164', 'model' => 'BIHUN IKAN TUNA 5 KG BARU', 'status' => 'Active'],
            ['seq' => '1609166', 'model' => 'BIHUN PIRING MAS 5 KG', 'status' => 'Active'],
            ['seq' => '1609173', 'model' => 'BOX JAGUNG SRIKAYA 5 KG', 'status' => 'Active'],
            ['seq' => '1609181', 'model' => 'BIHUN POHON KOPI 5 KG', 'status' => 'Active'],
            ['seq' => '1609182', 'model' => 'BIHUN DELLIS 5 KG', 'status' => 'Active'],
            ['seq' => '1609185', 'model' => 'POLOS UK 506 X 356 X 407', 'status' => 'Active'],
            ['seq' => '1609186', 'model' => 'POLOS 480 X 410 X 401', 'status' => 'Active'],
        ];

        foreach ($masterCards as $data) {
            DB::table('master_cards')->insert($data);
        }
    }
} 