<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Cash and Bank Accounts
            [ 
                'account_number' => '111101-00-00-00', 
                'dept' => '00', 
                'sub_dept' => '00', 
                'name' => 'KAS RUPIAH',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [ 
                'account_number' => '111102-00-00-00', 
                'dept' => '00', 
                'sub_dept' => '00', 
                'name' => 'KAS USD',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BCA NO.1083003786',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-01',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK PERMATA NO.8250780088',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-02',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BCA USD NO.066564488 (OBSOLETE)',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-03',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK NISP NO.020010639319',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-04',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK HSBC USD NO.900-0-02467-115',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-05',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK PANIN NO.11701111059',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-06',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK DANAMON NO.006000043',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-07',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK DANAMON NO.002600430',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-08',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BCA NO.862013385',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-12',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK MANDIRI NO.178000348970.3',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-13',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK MANDIRI NO.128001312962',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-14',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BCA NO.7130999829',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-15',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK MANDIRI NO.1330010505869',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-16',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BTPN NO.11127111201',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111202-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK NISP USD NO.020015939319',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111202-00-00-01',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK DANAMON USD NO.0559780967',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111202-00-00-02',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK PERMATA USD NO.0002623',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111202-00-00-03',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BCA USD NO.066114488',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111202-00-00-04',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK HSBC USD NO.900-0-02467-115',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111301-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'DEPOSITO (IDR)',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111401-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'POS SILANG',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111402-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'KAS DALAM PERJALANAN',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '112001-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'PIUTANG USAHA',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
            // Inventory Accounts
            [ 
                'account_number' => '114000-04-00-00', 
                'dept' => '00', 
                'sub_dept' => '00', 
                'name' => 'INVENTORY',
                'status' => 'A-Act',
                'ac_type' => 'P-Posting Account',
                'control_ac' => 'B-Balance Sheet'
            ],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::updateOrCreate(
                ['account_number' => $account['account_number']],
                $account
            );
        }
    }
}

