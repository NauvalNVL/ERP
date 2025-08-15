<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaserApprovalFlow;

class PurchaserApprovalFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $approvalFlows = [
            // ACC (Accounting) approval flow
            [
                'purchaser_id' => 'ACC',
                'approver_id' => 'acc09',
                'approver_name' => 'Nuri',
                'level_1' => true,
                'level_2' => false,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'ACC',
                'approver_id' => 'bgnkl',
                'approver_name' => 'BENGKEL',
                'level_1' => false,
                'level_2' => true,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'ACC',
                'approver_id' => 'matl03',
                'approver_name' => 'Marsh',
                'level_1' => false,
                'level_2' => false,
                'level_3' => true,
                'email_notification' => false,
            ],

            // CHRS (Christine) approval flow
            [
                'purchaser_id' => 'CHRS',
                'approver_id' => 'matl04',
                'approver_name' => 'Christine',
                'level_1' => true,
                'level_2' => false,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'CHRS',
                'approver_id' => 'matl07',
                'approver_name' => 'Nikita',
                'level_1' => false,
                'level_2' => true,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'CHRS',
                'approver_id' => 'matl12',
                'approver_name' => 'Yufa',
                'level_1' => false,
                'level_2' => false,
                'level_3' => true,
                'email_notification' => false,
            ],

            // CORR (Corrugation) approval flow
            [
                'purchaser_id' => 'CORR',
                'approver_id' => 'matl16',
                'approver_name' => 'Yuda',
                'level_1' => true,
                'level_2' => false,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'CORR',
                'approver_id' => 'mist04',
                'approver_name' => 'Nyoman',
                'level_1' => false,
                'level_2' => true,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'CORR',
                'approver_id' => 'mist07',
                'approver_name' => 'Sakka',
                'level_1' => false,
                'level_2' => false,
                'level_3' => true,
                'email_notification' => false,
            ],

            // DISPH (Dispatch) approval flow
            [
                'purchaser_id' => 'DISPH',
                'approver_id' => 'mkt05',
                'approver_name' => 'Endang',
                'level_1' => true,
                'level_2' => false,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'DISPH',
                'approver_id' => 'pa',
                'approver_name' => 'paa',
                'level_1' => false,
                'level_2' => true,
                'level_3' => false,
                'email_notification' => false,
            ],

            // HRD approval flow
            [
                'purchaser_id' => 'HRD',
                'approver_id' => 'acc09',
                'approver_name' => 'Nuri',
                'level_1' => true,
                'level_2' => false,
                'level_3' => false,
                'email_notification' => false,
            ],
            [
                'purchaser_id' => 'HRD',
                'approver_id' => 'matl04',
                'approver_name' => 'Christine',
                'level_1' => false,
                'level_2' => true,
                'level_3' => false,
                'email_notification' => false,
            ],
        ];

        foreach ($approvalFlows as $flowData) {
            PurchaserApprovalFlow::create($flowData);
        }
    }
} 