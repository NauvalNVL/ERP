<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaperFlute;
use App\Models\SideTrimByFlute;

class SideTrimByFluteSeeder extends Seeder
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

        $count = 0;
        
        // Create sample data for each flute
        foreach ($flutes as $flute) {
            // Create a non-composite entry
            SideTrimByFlute::updateOrCreate(
                [
                    'flute_id' => $flute->id,
                    'is_composite' => false,
                ],
                [
                    'length_add' => rand(10, 30),
                    'length_less' => rand(5, 20),
                ]
            );
            $count++;
            
            // Create a composite entry
            SideTrimByFlute::updateOrCreate(
                [
                    'flute_id' => $flute->id,
                    'is_composite' => true,
                ],
                [
                    'length_add' => rand(20, 40),
                    'length_less' => rand(10, 25),
                ]
            );
            $count++;
        }
        
        $this->command->info("Created $count side trim by flute records.");
    }
} 