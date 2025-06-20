<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductDesign;

class ProductDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productDesigns = [
            [
                'pd_code' => 'APP',
                'pd_name' => 'APP',
                'pd_design_type' => 'T-Trading',
                'idc' => 'NA',
                'product' => '005',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'BO',
                'pd_name' => 'BO/BO',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0510',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'BO DJ',
                'pd_name' => 'BO/BO DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0510J2',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'BO/B1',
                'pd_name' => 'B1/BO FLIP BANAR',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0200B',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'BO/B1 4J',
                'pd_name' => 'BO/B1 4 JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0204B',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '4',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'No',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'BO/B1 DJ',
                'pd_name' => 'BO/B1 DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0202B',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1',
                'pd_name' => 'REGULAR BOX',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1 4J',
                'pd_name' => 'B1 4 JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201a',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '4',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1 4J 2C',
                'pd_name' => 'B1 4 JOINT + CREASING',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201s',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '4',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1 DJ',
                'pd_name' => 'B1 DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201J2',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1 DJ FR',
                'pd_name' => 'B1 DOUBLE JOINT FLUTE REVERSE',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201R',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Reverse',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1 DJ+',
                'pd_name' => 'B1 DOUBLE JOINT + CREASING',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201s',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1+1CR',
                'pd_name' => 'B1+1CREASING',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201s',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1+1CRDJ',
                'pd_name' => 'B1+1CREASING DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201J2',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1+2CR',
                'pd_name' => 'B1+2CREASING',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201c2',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1-FR',
                'pd_name' => 'REGULAR BOX FLUTE REVERSE',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0201R',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Reverse',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1/BO',
                'pd_name' => 'B1/BO FLIP ATAS',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0200T',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1/BO 4J',
                'pd_name' => 'B1/BO 4 JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0204T',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '4',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B1/BO DJ',
                'pd_name' => 'B1/BO DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '020J2T',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B3',
                'pd_name' => 'FULL OSC',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0203',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B3 DJ',
                'pd_name' => 'B3 DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0203J2',
                'product' => '001',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B3 FR',
                'pd_name' => 'FULL OSC FLUTE REVERSE',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0203R',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B4',
                'pd_name' => 'CENTER SPECIAL SLOTTED CARTON',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '0204',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'Yes',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'B5',
                'pd_name' => 'CENTER SPECIAL FULL OSC',
                'pd_design_type' => 'M-Manufacture',
                'idc' => '020E',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'BR',
                'pd_name' => 'BUTT ROLL',
                'pd_design_type' => 'T-Trading',
                'idc' => 'SB',
                'product' => '003',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'CB',
                'pd_name' => 'SHEET PELL LEBAR',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'CB',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'CB-P',
                'pd_name' => 'SHEET PELL PANJANG',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'CP',
                'product' => '002',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'CONES',
                'pd_name' => 'CONES',
                'pd_design_type' => 'T-Trading',
                'idc' => 'NA',
                'product' => '006',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'COREPLUG',
                'pd_name' => 'CORE PLUG',
                'pd_design_type' => 'T-Trading',
                'idc' => 'NA',
                'product' => '015',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'CP',
                'pd_name' => 'CREASE PAD',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'CP',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'DP-DJ',
                'pd_name' => 'DIGITAL PRINT - DOUBLE JOINT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'DC',
                'product' => '015',
                'joint' => 'Yes',
                'joint_to_print' => 'Yes',
                'pcs_to_joint' => '2',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'No',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'DP-PUNCH',
                'pd_name' => 'DIGITAL PRINT - DIECUT',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'DC',
                'product' => '015',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'Yes',
                'flute_style' => 'Normal',
                'print_flute' => 'No',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'ETC',
                'pd_name' => 'PENJUALAN LAIN-LAIN PCS',
                'pd_design_type' => 'T-Trading',
                'idc' => 'SB',
                'product' => '005',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'ETC-KG',
                'pd_name' => 'PENJUALAN LAIN-LAIN KG',
                'pd_design_type' => 'T-Trading',
                'idc' => 'SB',
                'product' => '005',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'ETC-R',
                'pd_name' => 'PENDAPATAN LAIN-LAIN',
                'pd_design_type' => 'T-Trading',
                'idc' => 'NA',
                'product' => '013',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'N/A',
                'print_flute' => 'No',
                'input_weight' => 'Yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'LAYER',
                'pd_name' => 'LAYER',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'PAD',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'LAYER CR',
                'pd_name' => 'LAYER CREASING/LAYER KELILING',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'CP',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'LY CR-FR',
                'pd_name' => 'LAYER CREASING FLUTE TERBALIK',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'CP',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Reverse',
                'print_flute' => 'Yes',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pd_code' => 'Layer SF',
                'pd_name' => 'Layer Single Face',
                'pd_design_type' => 'M-Manufacture',
                'idc' => 'T-DC',
                'product' => '001',
                'joint' => 'No',
                'joint_to_print' => 'No',
                'pcs_to_joint' => '0',
                'score' => 'Yes',
                'slot' => 'No',
                'flute_style' => 'Normal',
                'print_flute' => 'No',
                'input_weight' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert or update the product designs
        foreach ($productDesigns as $design) {
            $pdCode = $design['pd_code'];
            
            // Check if the record already exists
            $existingDesign = ProductDesign::where('pd_code', $pdCode)->first();
            
            if ($existingDesign) {
                // Update existing record
                $existingDesign->update($design);
                $this->command->info("Updated product design: {$pdCode}");
            } else {
                // Create new record
                ProductDesign::create($design);
                $this->command->info("Created product design: {$pdCode}");
            }
        }
    }
} 