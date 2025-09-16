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
                'code' => '86768',
                'name' => 'hfgj',
                'scores' => 1,
                'gap' => 0.5,
                'specification' => '8',
                'description' => 'fjfghjk',
                'is_active' => true,
            ],
        [
            'code' => '5',
            'name' => 'tess1',
            'scores' => 1,
            'gap' => 0.5,
            'specification' => '',
            'description' => '',
            'is_active' => true,
        ],
        [
            'code' => '1',
            'name' => 'MALE MALE',
            'scores' => 1,
            'gap' => 0.5,
            'specification' => '',
            'description' => '',
            'is_active' => true,
        ],
        [
            'code' => '2',
            'name' => 'MALE FEMALE 10MM',
            'scores' => 1,
            'gap' => 0.5,
            'specification' => '',
            'description' => '',
            'is_active' => true,
        ],
        [
            'code' => '3',
            'name' => 'MALE FEMALE 10MM',
            'scores' => 0,
            'gap' => 0,
            'specification' => '',
            'description' => 'MALE FEMALE 10MM',
            'is_active' => true,
        ],
        [
            'code' => '4',
            'name' => 'MALE FEMALE 9MM',
            'scores' => 1,
            'gap' => 0,
            'specification' => '',
            'description' => 'MALE FEMALE 9MM',
            'is_active' => true,
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