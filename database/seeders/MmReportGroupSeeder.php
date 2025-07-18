<?php

namespace Database\Seeders;

use App\Models\MmReportGroup;
use Illuminate\Database\Seeder;

class MmReportGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportGroups = [
            [
                'code' => '01',
                'name' => 'GROUP 01',
                'is_active' => true,
            ],
            [
                'code' => '02',
                'name' => 'GROUP 02',
                'is_active' => true,
            ],
            [
                'code' => '03',
                'name' => 'GROUP 03',
                'is_active' => true,
            ],
            [
                'code' => '04',
                'name' => 'GROUP 04',
                'is_active' => true,
            ],
        ];

        foreach ($reportGroups as $group) {
            MmReportGroup::updateOrCreate(
                ['code' => $group['code']],
                $group
            );
        }
    }
} 