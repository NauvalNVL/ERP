<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RollTrimByCorrugator;
use App\Models\PaperFlute;

class RollTrimByCorrugatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all paper flutes
        $paperFlutes = PaperFlute::all();
        
        // Create roll trim data for each flute
        foreach ($paperFlutes as $flute) {
            // Generate random trim values
            $minTrim = rand(5, 15);
            $maxTrim = rand($minTrim + 5, $minTrim + 20);
            
            RollTrimByCorrugator::updateOrCreate(
                [
                    'flute_id' => $flute->id,
                ],
                [
                    'compute' => rand(0, 1) == 1, // Randomly set compute to true or false
                    'min_trim' => $minTrim,
                    'max_trim' => $maxTrim,
                    'created_by' => 'SYSTEM',
                    'updated_by' => 'SYSTEM',
                ]
            );
        }
        
        $this->command->info('Roll trim by corrugator data seeded successfully!');
    }
} 