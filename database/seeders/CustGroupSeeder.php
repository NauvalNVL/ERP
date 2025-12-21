<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustGroup;

class CustGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['Group_ID' => '01', 'Group_Name' => 'Customer Group 01', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 01', 'status' => 'Act'],
            ['Group_ID' => '02', 'Group_Name' => 'Customer Group 02', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 02', 'status' => 'Act'],
            ['Group_ID' => '03', 'Group_Name' => 'Customer Group 03', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 03', 'status' => 'Act'],
            ['Group_ID' => '04', 'Group_Name' => 'Customer Group 04', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 04', 'status' => 'Act'],
            ['Group_ID' => '05', 'Group_Name' => 'Customer Group 05', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 05', 'status' => 'Act'],
            ['Group_ID' => '06', 'Group_Name' => 'Customer Group 06', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 06', 'status' => 'Act'],
            ['Group_ID' => '07', 'Group_Name' => 'Customer Group 07', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 07', 'status' => 'Act'],
            ['Group_ID' => '08', 'Group_Name' => 'Customer Group 08', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 08', 'status' => 'Act'],
            ['Group_ID' => '09', 'Group_Name' => 'Customer Group 09', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 09', 'status' => 'Act'],
            ['Group_ID' => '10', 'Group_Name' => 'Customer Group 10', 'Currency' => 'IDR', 'AC' => null, 'Name' => 'Group 10', 'status' => 'Act'],
        ];

        foreach ($groups as $group) {
            CustGroup::updateOrCreate(
                ['Group_ID' => $group['Group_ID']],
                $group
            );
        }
    }
}
