<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerGroup;
use App\Models\UserCps;
use Illuminate\Support\Facades\DB;

class CustomerGroupSeeder extends Seeder
{
    public function run()
    {
        // Get a default user ID from USERCPS (legacy table with string ID)
        $defaultUser = UserCps::first();
        $userId = $defaultUser ? $defaultUser->ID : 'SYSTEM';

        // Clear existing data
        DB::table('customer_groups')->truncate();

        $customerGroups = [
            [
                'group_code' => '01',
                'description' => 'PIUTANG TRAILER',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_code' => '02',
                'description' => 'PIUTANG WASTE',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_code' => '03',
                'description' => 'PIUTANG USAHA PENDAPATAN LAIN2',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_code' => 'NA',
                'description' => 'PIUTANG USAHA',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($customerGroups as $group) {
            DB::table('customer_groups')->insert($group);
        }
    }
}
