<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerGroup;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerGroupSeeder extends Seeder
{
    public function run()
    {
        // Get a default user for created_by and updated_by
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        // Clear existing data
        DB::table('customer_groups')->truncate();

        $customerGroups = [
            [
                'group_code' => '01',
                'description' => 'PIUTANG TRAILER',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_code' => '02',
                'description' => 'PIUTANG WASTE',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_code' => '03',
                'description' => 'PIUTANG USAHA PENDAPATAN LAIN2',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_code' => 'NA',
                'description' => 'PIUTANG USAHA',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($customerGroups as $group) {
            DB::table('customer_groups')->insert($group);
        }
    }
}
