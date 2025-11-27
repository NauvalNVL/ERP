<?php

namespace Database\Seeders;

use App\Models\ScoringTool;
use Illuminate\Database\Seeder;

class ScoringToolSeeder extends Seeder
{
    /**
     * The scoring tools data from CPS Enterprise 2020.
     * Structure: CODE, NAME, SCORER GAP
     */
    protected $scoringTools = [
        [
            'code' => 'P',
            'name' => 'N/A',
            'scorer_gap' => 0.0,
            'status' => 'Act'
        ],
        [
            'code' => '1',
            'name' => 'MALE FEMAIL 10MM',
            'scorer_gap' => 0.0,
            'status' => 'Act'
        ],
        [
            'code' => '2',
            'name' => 'MALE PHAT',
            'scorer_gap' => 0.0,
            'status' => 'Act'
        ],
        [
            'code' => '3',
            'name' => 'MALE FEMAIL 8MM',
            'scorer_gap' => 0.0,
            'status' => 'Act'
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->scoringTools as $tool) {
            ScoringTool::updateOrCreate(
                ['code' => $tool['code']],
                $tool
            );
        }
    }

    /**
     * Get the seeder data.
     */
    public function getSeederData()
    {
        return $this->scoringTools;
    }
} 