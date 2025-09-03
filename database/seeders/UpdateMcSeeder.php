<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterCard;

class UpdateMcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // Data sesuai dengan gambar Master Card Table - Customer ABDULLAH, BPK (000211-08)
            ['mc_seq' => '1609138', 'mc_model' => 'BOX BASO 4,5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609144', 'mc_model' => 'BOX IKAN HARIMAU 4.5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609145', 'mc_model' => 'BOX SRIKAYA 4.5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609162', 'mc_model' => 'BIHUN FANIA 5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609163', 'mc_model' => 'BIHUN IKAN TUNA 4.5 KG BARU', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609164', 'mc_model' => 'BIHUN IKAN TUNA 5 KG BARU', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609166', 'mc_model' => 'BIHUN PIRING MAS 5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609173', 'mc_model' => 'BOX JAGUNG SRIKAYA 5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609181', 'mc_model' => 'BIHUN POHON KOPI 5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609182', 'mc_model' => 'BIHUN DELLIS 5 KG', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '396', 'ext_dim_2' => '243', 'ext_dim_3' => '297', 'int_dim_1' => '393', 'int_dim_2' => '240', 'int_dim_3' => '292', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609185', 'mc_model' => 'POLOS UK 506 X 356 X 407', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '506', 'ext_dim_2' => '356', 'ext_dim_3' => '407', 'int_dim_1' => '503', 'int_dim_2' => '353', 'int_dim_3' => '402', 'customer_code' => '000211-08'],
            ['mc_seq' => '1609186', 'mc_model' => 'POLOS 480 X 410 X 401', 'part_no' => 'BOX', 'comp_no' => 'Main', 'p_design' => 'B1', 'status' => 'Active', 'ext_dim_1' => '480', 'ext_dim_2' => '410', 'ext_dim_3' => '401', 'int_dim_1' => '477', 'int_dim_2' => '407', 'int_dim_3' => '396', 'customer_code' => '000211-08'],
        ];

        foreach ($data as $item) {
            try {
                MasterCard::updateOrCreate(
                    ['mc_seq' => $item['mc_seq']], // kondisi pencarian
                    $item // data yang akan diupdate atau dibuat
                );
            } catch (\Exception $e) {
                $this->command->error("Error inserting record {$item['mc_seq']}: " . $e->getMessage());
            }
        }

        $this->command->info('Update MC seeder completed successfully!');
    }
}
