<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaperFlute;
use App\Models\RollSize;

class RollSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all flutes
        $flutes = PaperFlute::all();
        
        if ($flutes->count() === 0) {
            $this->command->info('No flutes found. Please run PaperFluteSeeder first.');
            return;
        }

        // Define roll lengths and their composite status
        $rollLengths = [
            '740' => false,
            '860' => true,
            '905' => true,
            '965' => true,
            '1040' => true,
            '1180' => true,
            '1290' => true,
            '1340' => true,
            '1450' => true,
            '1750' => true,
            '1950' => true,
            '1480' => true,
            '1540' => true,
            '1580' => true,
            '1900' => true,
        ];
        
        $count = 0;
        
        // Create sample data for each flute
        foreach ($flutes as $flute) {
            foreach ($rollLengths as $length => $isComposite) {
                RollSize::updateOrCreate(
                    [
                        'flute_id' => $flute->id,
                        'roll_length' => $length,
                    ],
                    [
                        'is_composite' => $isComposite,
                    ]
                );
                $count++;
            }
        }
        
        $this->command->info("Created $count roll size records.");
    }
} 