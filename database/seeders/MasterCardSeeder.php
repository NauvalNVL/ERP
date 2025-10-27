<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterCard;
use Illuminate\Support\Facades\Schema;

class MasterCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('master_cards')) {
            // Skip seeding if legacy table is not present in this environment
            return;
        }

        $masterCards = [
            [
                'mc_seq' => '1609138',
                'mc_model' => 'BOX BASO 4.5 KG',
                'mc_short_model' => 'BB45',
                'status' => 'Active',
                'customer_code' => '000211-08',
                'mc_approval' => 'No',
                'part_no' => 'PART-1609138',
                'comp_no' => 'COMP-001',
                'p_design' => 'PD-BASO-001',
                'ext_dim_1' => '450',
                'ext_dim_2' => '300',
                'ext_dim_3' => '200',
                'int_dim_1' => '440',
                'int_dim_2' => '290',
                'int_dim_3' => '190',
                'detailed_master_card' => [
                    'material' => 'Kraft Paper',
                    'thickness' => '0.3mm',
                    'color' => 'Brown',
                    'special_requirements' => 'Food grade approved'
                ],
                'pd_setup' => [
                    'partNo' => 'PART-1609138',
                    'selectedProductDesign' => 'BASO-001',
                    'selectedPaperFlute' => 'B',
                    'selectedChemicalCoat' => 'Food Grade',
                    'selectedReinforcementTape' => 'Standard',
                    'selectedPaperSize' => 'A4',
                    'selectedScoringToolCode' => 'ST-001',
                    'printColorCodes' => ['C', 'M', 'Y', 'K', '', '', ''],
                    'scoreL' => [50, 100, 150, 200, 250, 300, 350, 400, 450, 0],
                    'scoreW' => [30, 60, 90, 120, 150, 180, 210, 240, 270, 0],
                    'sheetLength' => '500',
                    'sheetWidth' => '400',
                    'conOut' => 'CO-001',
                    'convDuctX2' => 'CD-001',
                    'pcsToJoint' => '2',
                    'id' => ['L' => '440', 'W' => '290', 'H' => '190'],
                    'ed' => ['L' => '450', 'W' => '300', 'H' => '200'],
                    'pcsPerSet' => '1',
                    'creaseValue' => '2.5',
                    'nestSlot' => 'NS-001',
                    'dcutSheet' => ['L' => '500', 'W' => '400'],
                    'dcutMould' => ['L' => '500', 'W' => '400'],
                    'dcutBlockNo' => 'DB-001',
                    'pitBlockNo' => 'PB-001',
                    'stitchWirePieces' => '4',
                    'bdlPerPallet' => '50',
                    'peelOffPercent' => '0.5',
                    'itemRemark' => 'Food packaging - Baso 4.5kg',
                    'handHole' => false,
                    'rotaryDCut' => true,
                    'fullBlockPrint' => true,
                    'selectedFinishingCode' => 'FIN-001',
                    'selectedStitchWireCode' => 'SW-001',
                    'selectedBundlingStringCode' => 'BS-001',
                    'bundlingStringQty' => '2',
                    'selectedGlueingCode' => 'GL-001',
                    'selectedWrappingCode' => 'WR-001',
                    'moreDescriptions' => ['Food grade', 'Moisture resistant'],
                    'subMaterials' => ['Liner', 'Flute'],
                    'soValues' => ['DB125', 'B150', '1L', 'ACE', '2L'],
                    'woValues' => ['DB120', 'B140', '1L', 'ACE', '2L'],
                    'components' => [
                        ['c_num' => 'C001', 'pd' => 'BASO-001', 'pcs_set' => '1', 'part_num' => 'PART-1609138-01'],
                        ['c_num' => 'C002', 'pd' => 'BASO-001', 'pcs_set' => '1', 'part_num' => 'PART-1609138-02']
                    ]
                ]
            ],
            [
                'mc_seq' => '1609144',
                'mc_model' => 'BOX IKAN HARIMAU 4.5 KG',
                'mc_short_model' => 'BIH45',
                'status' => 'Active',
                'customer_code' => '000211-08',
                'mc_approval' => 'Yes',
                'part_no' => 'PART-1609144',
                'comp_no' => 'COMP-002',
                'p_design' => 'PD-IKAN-001',
                'ext_dim_1' => '480',
                'ext_dim_2' => '320',
                'ext_dim_3' => '220',
                'int_dim_1' => '470',
                'int_dim_2' => '310',
                'int_dim_3' => '210',
                'detailed_master_card' => [
                    'material' => 'Kraft Paper',
                    'thickness' => '0.35mm',
                    'color' => 'Brown',
                    'special_requirements' => 'Fish packaging approved'
                ],
                'pd_setup' => [
                    'partNo' => 'PART-1609144',
                    'selectedProductDesign' => 'IKAN-001',
                    'selectedPaperFlute' => 'C',
                    'selectedChemicalCoat' => 'Fish Grade',
                    'selectedReinforcementTape' => 'Heavy Duty',
                    'selectedPaperSize' => 'A3',
                    'selectedScoringToolCode' => 'ST-002',
                    'printColorCodes' => ['C', 'M', 'Y', 'K', 'P', '', ''],
                    'scoreL' => [60, 120, 180, 240, 300, 360, 420, 480, 0, 0],
                    'scoreW' => [40, 80, 120, 160, 200, 240, 280, 320, 0, 0],
                    'sheetLength' => '600',
                    'sheetWidth' => '500',
                    'conOut' => 'CO-002',
                    'convDuctX2' => 'CD-002',
                    'pcsToJoint' => '3',
                    'id' => ['L' => '470', 'W' => '310', 'H' => '210'],
                    'ed' => ['L' => '480', 'W' => '320', 'H' => '220'],
                    'pcsPerSet' => '1',
                    'creaseValue' => '3.0',
                    'nestSlot' => 'NS-002',
                    'dcutSheet' => ['L' => '600', 'W' => '500'],
                    'dcutMould' => ['L' => '600', 'W' => '500'],
                    'dcutBlockNo' => 'DB-002',
                    'pitBlockNo' => 'PB-002',
                    'stitchWirePieces' => '6',
                    'bdlPerPallet' => '40',
                    'peelOffPercent' => '0.8',
                    'itemRemark' => 'Fish packaging - Ikan Harimau 4.5kg',
                    'handHole' => true,
                    'rotaryDCut' => false,
                    'fullBlockPrint' => true,
                    'selectedFinishingCode' => 'FIN-002',
                    'selectedStitchWireCode' => 'SW-002',
                    'selectedBundlingStringCode' => 'BS-002',
                    'bundlingStringQty' => '3',
                    'selectedGlueingCode' => 'GL-002',
                    'selectedWrappingCode' => 'WR-002',
                    'moreDescriptions' => ['Fish grade', 'Odor resistant'],
                    'subMaterials' => ['Liner', 'Flute', 'Coating'],
                    'soValues' => ['DB130', 'B160', '1L', 'ACE', '2L'],
                    'woValues' => ['DB128', 'B155', '1L', 'ACE', '2L'],
                    'components' => [
                        ['c_num' => 'C001', 'pd' => 'IKAN-001', 'pcs_set' => '1', 'part_num' => 'PART-1609144-01']
                    ]
                ]
            ],
            [
                'mc_seq' => '1609145',
                'mc_model' => 'BOX SRIKAYA 4.5 KG',
                'mc_short_model' => 'BS45',
                'status' => 'Active',
                'customer_code' => '000211-08',
                'mc_approval' => 'No',
                'part_no' => 'PART-1609145',
                'comp_no' => 'COMP-003',
                'p_design' => 'PD-SRIKAYA-001',
                'ext_dim_1' => '460',
                'ext_dim_2' => '310',
                'ext_dim_3' => '210',
                'int_dim_1' => '450',
                'int_dim_2' => '300',
                'int_dim_3' => '200',
                'detailed_master_card' => [
                    'material' => 'Kraft Paper',
                    'thickness' => '0.32mm',
                    'color' => 'Brown',
                    'special_requirements' => 'Food packaging approved'
                ],
                'pd_setup' => [
                    'partNo' => 'PART-1609145',
                    'selectedProductDesign' => 'SRIKAYA-001',
                    'selectedPaperFlute' => 'B',
                    'selectedChemicalCoat' => 'Food Grade',
                    'selectedReinforcementTape' => 'Standard',
                    'selectedPaperSize' => 'A4',
                    'selectedScoringToolCode' => 'ST-003',
                    'printColorCodes' => ['C', 'M', 'Y', '', '', '', ''],
                    'scoreL' => [55, 110, 165, 220, 275, 330, 385, 440, 0, 0],
                    'scoreW' => [35, 70, 105, 140, 175, 210, 245, 280, 0, 0],
                    'sheetLength' => '550',
                    'sheetWidth' => '450',
                    'conOut' => 'CO-003',
                    'convDuctX2' => 'CD-003',
                    'pcsToJoint' => '2',
                    'id' => ['L' => '450', 'W' => '300', 'H' => '200'],
                    'ed' => ['L' => '460', 'W' => '310', 'H' => '210'],
                    'pcsPerSet' => '1',
                    'creaseValue' => '2.8',
                    'nestSlot' => 'NS-003',
                    'dcutSheet' => ['L' => '550', 'W' => '450'],
                    'dcutMould' => ['L' => '550', 'W' => '450'],
                    'dcutBlockNo' => 'DB-003',
                    'pitBlockNo' => 'PB-003',
                    'stitchWirePieces' => '4',
                    'bdlPerPallet' => '60',
                    'peelOffPercent' => '0.6',
                    'itemRemark' => 'Food packaging - Srikaya 4.5kg',
                    'handHole' => false,
                    'rotaryDCut' => true,
                    'fullBlockPrint' => false,
                    'selectedFinishingCode' => 'FIN-003',
                    'selectedStitchWireCode' => 'SW-003',
                    'selectedBundlingStringCode' => 'BS-003',
                    'bundlingStringQty' => '2',
                    'selectedGlueingCode' => 'GL-003',
                    'selectedWrappingCode' => 'WR-003',
                    'moreDescriptions' => ['Food grade', 'Sweet product packaging'],
                    'subMaterials' => ['Liner', 'Flute'],
                    'soValues' => ['DB122', 'B145', '1L', 'ACE', '2L'],
                    'woValues' => ['DB120', 'B140', '1L', 'ACE', '2L'],
                    'components' => [
                        ['c_num' => 'C001', 'pd' => 'SRIKAYA-001', 'pcs_set' => '1', 'part_num' => 'PART-1609145-01']
                    ]
                ]
            ]
        ];

        foreach ($masterCards as $data) {
            MasterCard::updateOrCreate(
                ['mc_seq' => $data['mc_seq']],
                $data
            );
        }
    }
} 