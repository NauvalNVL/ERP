<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SkuItemNoteAnalysisCode;
use App\Models\SkuItemNoteAnalysisGroup;

class SkuItemNoteAnalysisCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing analysis groups
        $qualityGroup = SkuItemNoteAnalysisGroup::where('group_code', 'QC-001')->first();
        $supplierGroup = SkuItemNoteAnalysisGroup::where('group_code', 'SUP-001')->first();
        $storageGroup = SkuItemNoteAnalysisGroup::where('group_code', 'STG-001')->first();
        $procurementGroup = SkuItemNoteAnalysisGroup::where('group_code', 'PRC-001')->first();
        $logisticsGroup = SkuItemNoteAnalysisGroup::where('group_code', 'LOG-001')->first();

        $analysisCodes = [];

        // Quality Control Analysis Codes
        if ($qualityGroup) {
            $analysisCodes = array_merge($analysisCodes, [
                [
                    'analysis_group_id' => $qualityGroup->id,
                    'analysis_code' => 'DMG-PKG',
                    'code_name' => 'Damaged Packaging',
                    'description' => 'Items with damaged or compromised packaging',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $qualityGroup->id,
                    'analysis_code' => 'WRG-LBL',
                    'code_name' => 'Wrong Label',
                    'description' => 'Items with incorrect or missing labels',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $qualityGroup->id,
                    'analysis_code' => 'EXP-STK',
                    'code_name' => 'Expired Stock',
                    'description' => 'Items that have exceeded expiration date',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $qualityGroup->id,
                    'analysis_code' => 'DEF-ITM',
                    'code_name' => 'Defective Item',
                    'description' => 'Items with manufacturing defects',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $qualityGroup->id,
                    'analysis_code' => 'INC-SPEC',
                    'code_name' => 'Incorrect Specification',
                    'description' => 'Items not meeting specified requirements',
                    'is_active' => true
                ]
            ]);
        }

        // Supplier Feedback Analysis Codes
        if ($supplierGroup) {
            $analysisCodes = array_merge($analysisCodes, [
                [
                    'analysis_group_id' => $supplierGroup->id,
                    'analysis_code' => 'LATE-DEL',
                    'code_name' => 'Late Delivery',
                    'description' => 'Supplier delivered items later than scheduled',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $supplierGroup->id,
                    'analysis_code' => 'INC-QTY',
                    'code_name' => 'Incorrect Quantity',
                    'description' => 'Delivered quantity does not match order',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $supplierGroup->id,
                    'analysis_code' => 'POOR-COM',
                    'code_name' => 'Poor Communication',
                    'description' => 'Issues with supplier communication',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $supplierGroup->id,
                    'analysis_code' => 'PRICE-VAR',
                    'code_name' => 'Price Variance',
                    'description' => 'Unexpected price changes from supplier',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $supplierGroup->id,
                    'analysis_code' => 'DOC-ERR',
                    'code_name' => 'Documentation Error',
                    'description' => 'Errors in supplier documentation',
                    'is_active' => true
                ]
            ]);
        }

        // Storage Condition Analysis Codes
        if ($storageGroup) {
            $analysisCodes = array_merge($analysisCodes, [
                [
                    'analysis_group_id' => $storageGroup->id,
                    'analysis_code' => 'TEMP-VAR',
                    'code_name' => 'Temperature Variance',
                    'description' => 'Storage temperature outside acceptable range',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $storageGroup->id,
                    'analysis_code' => 'HUM-ISS',
                    'code_name' => 'Humidity Issues',
                    'description' => 'Storage humidity problems affecting items',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $storageGroup->id,
                    'analysis_code' => 'PEST-CTL',
                    'code_name' => 'Pest Control',
                    'description' => 'Pest-related storage issues',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $storageGroup->id,
                    'analysis_code' => 'CLEAN-ISS',
                    'code_name' => 'Cleanliness Issues',
                    'description' => 'Storage area cleanliness problems',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $storageGroup->id,
                    'analysis_code' => 'SEC-BRCH',
                    'code_name' => 'Security Breach',
                    'description' => 'Security issues in storage area',
                    'is_active' => true
                ]
            ]);
        }

        // Procurement Analysis Codes
        if ($procurementGroup) {
            $analysisCodes = array_merge($analysisCodes, [
                [
                    'analysis_group_id' => $procurementGroup->id,
                    'analysis_code' => 'URG-REQ',
                    'code_name' => 'Urgent Request',
                    'description' => 'Items required urgently',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $procurementGroup->id,
                    'analysis_code' => 'COST-SAV',
                    'code_name' => 'Cost Saving',
                    'description' => 'Procurement cost saving opportunities',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $procurementGroup->id,
                    'analysis_code' => 'ALT-SUP',
                    'code_name' => 'Alternative Supplier',
                    'description' => 'Alternative supplier evaluation',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $procurementGroup->id,
                    'analysis_code' => 'BULK-ORD',
                    'code_name' => 'Bulk Order',
                    'description' => 'Bulk order considerations',
                    'is_active' => true
                ]
            ]);
        }

        // Logistics Analysis Codes
        if ($logisticsGroup) {
            $analysisCodes = array_merge($analysisCodes, [
                [
                    'analysis_group_id' => $logisticsGroup->id,
                    'analysis_code' => 'DMG-TRN',
                    'code_name' => 'Damage in Transit',
                    'description' => 'Items damaged during transportation',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $logisticsGroup->id,
                    'analysis_code' => 'DEL-DEL',
                    'code_name' => 'Delivery Delay',
                    'description' => 'Transportation delays affecting delivery',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $logisticsGroup->id,
                    'analysis_code' => 'WRG-LOC',
                    'code_name' => 'Wrong Location',
                    'description' => 'Items delivered to incorrect location',
                    'is_active' => true
                ],
                [
                    'analysis_group_id' => $logisticsGroup->id,
                    'analysis_code' => 'HAND-ISS',
                    'code_name' => 'Handling Issues',
                    'description' => 'Problems with item handling during transport',
                    'is_active' => true
                ]
            ]);
        }

        // Create analysis codes
        foreach ($analysisCodes as $code) {
            SkuItemNoteAnalysisCode::create($code);
        }
    }
} 