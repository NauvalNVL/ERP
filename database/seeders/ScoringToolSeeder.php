<?php

namespace Database\Seeders;

use App\Models\ScoringTool;
use Illuminate\Database\Seeder;

class ScoringToolSeeder extends Seeder
{
    /**
     * The scoring tools data.
     */
    protected $scoringTools = [
        [
            'code' => '1',
            'name' => 'N/A',
            'scores' => 0.0,
            'gap' => 0.0,
            'specification' => '',
            'description' => 'N/A',
            'is_active' => true,
        ],
                                [
                'code' => '2',
                'name' => 'MALE MALE',
                'scores' => 0,
                'gap' => 0,
                'specification' => '',
                'description' => 'MALE MALE',
                'is_active' => true,
            ],,,
                    [
                'code' => '3',
                'name' => 'MALE FEMALE 10MM',
                'scores' => 0.3,
                'gap' => 0,
                'specification' => '',
                'description' => 'MALE FEMALE 10MM',
                'is_active' => true,
            ],,
        [
            'code' => '4',
            'name' => 'MALE FEMALE 9MM',
            'scores' => 0.0,
            'gap' => 0.0,
            'specification' => '',
            'description' => 'MALE FEMALE 9MM',
            'is_active' => true,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->scoringTools as $tool) {
            ScoringTool::create($tool);
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