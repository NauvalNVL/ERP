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
            [ 
                'account_number' => '111101-00-00-00', 
                'dept' => '00', 
                'sub_dept' => '00', 
                'name' => 'KAS RUPIAH',
                'status' => 'A-Act',
                'control_ac' => 'B-Balance Sheet'
            ],
            [ 
                'account_number' => '111102-00-00-00', 
                'dept' => '00', 
                'sub_dept' => '00', 
                'name' => 'KAS USD',
                'status' => 'A-Act',
                'control_ac' => 'B-Balance Sheet'
            ],
            [ 
                'account_number' => '114000-04-00-00', 
                'dept' => '00', 
                'sub_dept' => '00', 
                'name' => 'INVENTORY',
                'status' => 'A-Act',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-00',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK BCA NO.1083003786',
                'status' => 'A-Act',
                'control_ac' => 'B-Balance Sheet'
            ],
            [
                'account_number' => '111201-00-00-01',
                'dept' => '00',
                'sub_dept' => '00',
                'name' => 'BANK PERMATA NO.0250780088',
                'status' => 'A-Act',
                'control_ac' => 'B-Balance Sheet'
            ],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::updateOrCreate(
                ['account_number' => $account['account_number']], // kondisi pencarian
                $account // data yang akan diupdate atau dibuat
            );
        }
    }
}
