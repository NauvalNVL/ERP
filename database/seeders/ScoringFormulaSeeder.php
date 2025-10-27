<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScoringFormula;
use App\Models\ProductDesign;
use App\Models\PaperFlute;
use Illuminate\Support\Facades\DB;

class ScoringFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('scoring_formulas')->truncate();

        // Get product design and paper flute IDs
        $productDesigns = ProductDesign::all();
        $paperFlutes = PaperFlute::all();

        // If no product designs or paper flutes exist, run their seeders first
        if ($productDesigns->isEmpty()) {
            $this->command->info('No product designs found. Running ProductDesignSeeder...');
            $this->call(ProductDesignSeeder::class);
            $productDesigns = ProductDesign::all();
        }

        if ($paperFlutes->isEmpty()) {
            $this->command->info('No paper flutes found. Running PaperFluteSeeder...');
            $this->call(PaperFluteSeeder::class);
            $paperFlutes = PaperFlute::all();
        }

        // Sample data for scoring formulas
        $scoringFormulas = [
            [
                'product_design' => 'B1',
                'paper_flute' => 'BF',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40'],
                    ['index' => 2, 'value' => 'L+7'],
                    ['index' => 3, 'value' => 'W+7'],
                    ['index' => 4, 'value' => 'L+5']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12'],
                    ['index' => 2, 'value' => 'H/2-5']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ],
            [
                'product_design' => 'B1 RJ',
                'paper_flute' => 'CF',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40'],
                    ['index' => 2, 'value' => 'L+7'],
                    ['index' => 3, 'value' => 'W+7']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12'],
                    ['index' => 2, 'value' => 'H/2-5']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ],
            [
                'product_design' => 'B1/B1 DJ',
                'paper_flute' => 'AF',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40'],
                    ['index' => 2, 'value' => 'L+7']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ],
            [
                'product_design' => 'B1/B1',
                'paper_flute' => 'AB',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ],
            [
                'product_design' => 'B1',
                'paper_flute' => 'BC',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40'],
                    ['index' => 2, 'value' => 'L+7'],
                    ['index' => 3, 'value' => 'W+7']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ],
            [
                'product_design' => 'B1',
                'paper_flute' => 'BEF',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40'],
                    ['index' => 2, 'value' => 'L+7']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ],
            [
                'product_design' => 'B1',
                'paper_flute' => 'EF',
                'scoring_length_formula' => [
                    ['index' => 1, 'value' => '40']
                ],
                'scoring_width_formula' => [
                    ['index' => 1, 'value' => 'H-12']
                ],
                'length_conversion' => 7.00,
                'width_conversion' => 7.00,
                'height_conversion' => 12.00
            ]
        ];

        foreach ($scoringFormulas as $formulaData) {
            // Find product design and paper flute by code
            $productDesign = $productDesigns->where('code', $formulaData['product_design'])->first();
            // PaperFlute model maps to Flute_CPS with column 'Flute'
            $paperFlute = $paperFlutes->where('Flute', $formulaData['paper_flute'])->first();

            // Skip if product design or paper flute not found
            if (!$productDesign || !$paperFlute) {
                $this->command->warn("Skipping formula for {$formulaData['product_design']} / {$formulaData['paper_flute']} - not found in database");
                continue;
            }

            // Create scoring formula
            ScoringFormula::create([
                'product_design_id' => $productDesign->id,
                'paper_flute_code' => $paperFlute->Flute,
                'scoring_length_formula' => $formulaData['scoring_length_formula'],
                'scoring_width_formula' => $formulaData['scoring_width_formula'],
                'length_conversion' => $formulaData['length_conversion'],
                'width_conversion' => $formulaData['width_conversion'],
                'height_conversion' => $formulaData['height_conversion'],
                'is_active' => true,
                'notes' => 'Seeded data'
            ]);
        }

        $this->command->info('Scoring Formula seeder completed successfully.');
    }
} 