<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterCard;

class MasterCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $masterCards = [
            // Semua Master Cards untuk Customer ABDULLAH, BPK (000211-08)
            ['mc_seq' => '1609138', 'mc_model' => 'BOX BASO 4.5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609144', 'mc_model' => 'BOX IKAN HARIMAU 4.5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609145', 'mc_model' => 'BOX SRIKAYA 4.5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609162', 'mc_model' => 'BIHUN FANIA 5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609163', 'mc_model' => 'BIHUN IKAN TUNA 4.5 KG BARU', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609164', 'mc_model' => 'BIHUN IKAN TUNA 5 KG BARU', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609166', 'mc_model' => 'BIHUN PIRING MAS 5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609173', 'mc_model' => 'BOX JAGUNG SRIKAYA 5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609181', 'mc_model' => 'BIHUN POHON KOPI 5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609182', 'mc_model' => 'BIHUN DELLIS 5 KG', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609185', 'mc_model' => 'POLOS UK 506 X 356 X 407', 'status' => 'Active', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609186', 'mc_model' => 'POLOS 480 X 410 X 401', 'status' => 'Active', 'customer_code' => '000211-08'],
        ];

        foreach ($masterCards as $data) {
            MasterCard::updateOrCreate(
                ['mc_seq' => $data['mc_seq']], // kondisi pencarian
                $data // data yang akan diupdate atau dibuat
            );
        }
    }
} 