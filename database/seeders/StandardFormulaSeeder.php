<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandardFormula;

class StandardFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing records to avoid duplicates
        StandardFormula::truncate();

        // Create default configuration
        StandardFormula::create([
            'activate_standard_formula' => 'yes',
            'economic_run_size' => 'average',
            'check_run_size_result' => true,
            'master_card' => 'free',
            'sales_order' => 'free',
            'work_order' => 'free',
        ]);
    }
} 