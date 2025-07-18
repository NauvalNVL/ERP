<?php

namespace Database\Seeders;

use App\Models\MmGlDistribution;
use Illuminate\Database\Seeder;

class MmGlDistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $glDistributions = [
            [
                'gl_dist_code' => 'INV',
                'gl_dist_name' => 'INVENTORY',
                'gl_account' => '114000-04-00-00',
                'gl_account_segment1' => '114000',
                'gl_account_segment2' => '04',
                'gl_account_segment3' => '00',
                'gl_account_name' => '* N/F *',
                'is_linked' => false,
            ],
            [
                'gl_dist_code' => 'RAW',
                'gl_dist_name' => 'RAW MATERIALS',
                'gl_account' => '114100-04-00-00',
                'gl_account_segment1' => '114100',
                'gl_account_segment2' => '04',
                'gl_account_segment3' => '00',
                'gl_account_name' => '* N/F *',
                'is_linked' => false,
            ],
            [
                'gl_dist_code' => 'WIP',
                'gl_dist_name' => 'WORK IN PROCESS',
                'gl_account' => '114200-04-00-00',
                'gl_account_segment1' => '114200',
                'gl_account_segment2' => '04',
                'gl_account_segment3' => '00',
                'gl_account_name' => '* N/F *',
                'is_linked' => false,
            ],
            [
                'gl_dist_code' => 'FG',
                'gl_dist_name' => 'FINISHED GOODS',
                'gl_account' => '114300-04-00-00',
                'gl_account_segment1' => '114300',
                'gl_account_segment2' => '04',
                'gl_account_segment3' => '00',
                'gl_account_name' => '* N/F *',
                'is_linked' => false,
            ],
        ];

        foreach ($glDistributions as $data) {
            MmGlDistribution::updateOrCreate(
                ['gl_dist_code' => $data['gl_dist_code']],
                $data
            );
        }
    }
} 