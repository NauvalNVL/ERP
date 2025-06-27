<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MmControlPeriod;

class MmControlPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default control period settings
        MmControlPeriod::firstOrCreate([
            'pr_forward_period' => '0',
            'pr_control_date' => '1',
            
            'po_current_period_month' => now()->month,
            'po_current_period_year' => now()->year,
            'po_forward_period' => '1',
            'po_control_date' => '1',
            'po_min_allow_percentage' => 0.00,
            'po_max_allow_percentage' => 0.00,
            'po_zero_po' => 'N',
            
            'inv_current_period_month' => now()->month,
            'inv_current_period_year' => now()->year,
            'inv_backward_period' => '0',
            'inv_control_date' => '1',
            'inv_zero_tran' => 'Y',
            
            'cost_current_period_month' => now()->month,
            'cost_current_period_year' => now()->year,
            'cost_control_date' => '1',
            'cost_y_allow_after_period' => true
        ]);
    }
} 