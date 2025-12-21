<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\DB;

class CustomerGroupSeeder extends Seeder
{
    /**
     * Seed CUST_GROUP table with default data from CPS
     */
    public function run()
    {
        // Customer groups from CPS system with sequential No
        $customerGroups = [
            [
                'No' => '1',
                'Group_ID' => '01',
                'Group_Name' => 'PHUTANG TRADER',
                'Currency' => null,
                'AC' => null,
                'Name' => null,
                'status' => 'Act'
            ],
            [
                'No' => '2',
                'Group_ID' => '02',
                'Group_Name' => 'PHUTANG USAHA PENDAPATAN LAIN1',
                'Currency' => null,
                'AC' => null,
                'Name' => null,
                'status' => 'Act'
            ],
            [
                'No' => '3',
                'Group_ID' => '03',
                'Group_Name' => 'PHUTANG USAHA PENDAPATAN LAIN2',
                'Currency' => null,
                'AC' => null,
                'Name' => null,
                'status' => 'Act'
            ],
            [
                'No' => '4',
                'Group_ID' => 'NA',
                'Group_Name' => 'PHUTANG USAHA',
                'Currency' => null,
                'AC' => null,
                'Name' => null,
                'status' => 'Act'
            ],
        ];

        foreach ($customerGroups as $group) {
            CustomerGroup::updateOrCreate(
                ['Group_ID' => $group['Group_ID']],
                $group
            );
        }
    }
}
