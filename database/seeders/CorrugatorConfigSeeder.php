<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CorrugatorConfig;

class CorrugatorConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default data for corrugator configs
        $configs = [
            [
                'corrugator_id' => '1',
                'min_sheet_length' => 1,
                'max_sheet_length' => 99999,
                'min_sheet_width' => 1,
                'max_sheet_width' => 99999,
                'is_sheet_length_multiplied' => true,
                'is_min_raw_multiplied' => false,
                'validate_sheet_width' => false,
            ],
            [
                'corrugator_id' => '2',
                'min_sheet_length' => 1,
                'max_sheet_length' => 99999,
                'min_sheet_width' => 1,
                'max_sheet_width' => 99999,
                'is_sheet_length_multiplied' => true,
                'is_min_raw_multiplied' => false,
                'validate_sheet_width' => false,
            ],
            [
                'corrugator_id' => '3',
                'min_sheet_length' => 1,
                'max_sheet_length' => 99999,
                'min_sheet_width' => 1,
                'max_sheet_width' => 99999,
                'is_sheet_length_multiplied' => true,
                'is_min_raw_multiplied' => false,
                'validate_sheet_width' => false,
            ],
        ];

        foreach ($configs as $config) {
            CorrugatorConfig::updateOrCreate(
                ['corrugator_id' => $config['corrugator_id']],
                $config
            );
        }
    }
} 