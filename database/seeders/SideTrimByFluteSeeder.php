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

        $sideTrimData = [
            ['flute_code' => 'A', 'compute' => false, 'length_add' => 22, 'length_less' => 20],
            ['flute_code' => 'A', 'compute' => true, 'length_add' => 35, 'length_less' => 23],
            ['flute_code' => 'AF', 'compute' => false, 'length_add' => 24, 'length_less' => 15],
            ['flute_code' => 'AF', 'compute' => true, 'length_add' => 36, 'length_less' => 15],
            ['flute_code' => 'BC', 'compute' => false, 'length_add' => 24, 'length_less' => 17],
            ['flute_code' => 'BC', 'compute' => true, 'length_add' => 39, 'length_less' => 12],
            ['flute_code' => 'BF2', 'compute' => false, 'length_add' => 28, 'length_less' => 11],
            ['flute_code' => 'BF2', 'compute' => true, 'length_add' => 28, 'length_less' => 22],
            ['flute_code' => 'BF', 'compute' => false, 'length_add' => 13, 'length_less' => 12],
            ['flute_code' => 'BF', 'compute' => true, 'length_add' => 24, 'length_less' => 15],
            ['flute_code' => 'B', 'compute' => false, 'length_add' => 10, 'length_less' => 19],
            ['flute_code' => 'B', 'compute' => true, 'length_add' => 37, 'length_less' => 22],
            ['flute_code' => 'BFS', 'compute' => false, 'length_add' => 27, 'length_less' => 7],
            ['flute_code' => 'BFS', 'compute' => true, 'length_add' => 33, 'length_less' => 11],
            ['flute_code' => 'BD', 'compute' => false, 'length_add' => 21, 'length_less' => 7],
            ['flute_code' => 'BD', 'compute' => true, 'length_add' => 38, 'length_less' => 18],
            ['flute_code' => 'CF', 'compute' => false, 'length_add' => 19, 'length_less' => 7],
            ['flute_code' => 'CF', 'compute' => true, 'length_add' => 35, 'length_less' => 25],
            ['flute_code' => 'CF2', 'compute' => false, 'length_add' => 28, 'length_less' => 19],
            ['flute_code' => 'CF2', 'compute' => true, 'length_add' => 26, 'length_less' => 20],
            ['flute_code' => 'CR', 'compute' => false, 'length_add' => 17, 'length_less' => 20],
            ['flute_code' => 'CR', 'compute' => true, 'length_add' => 27, 'length_less' => 15],
            ['flute_code' => 'DF', 'compute' => false, 'length_add' => 16, 'length_less' => 8],
            ['flute_code' => 'DF', 'compute' => true, 'length_add' => 21, 'length_less' => 24],
            ['flute_code' => 'E', 'compute' => false, 'length_add' => 27, 'length_less' => 6],
            ['flute_code' => 'E', 'compute' => true, 'length_add' => 32, 'length_less' => 14],
            ['flute_code' => 'EF', 'compute' => false, 'length_add' => 19, 'length_less' => 12],
            ['flute_code' => 'EF', 'compute' => true, 'length_add' => 31, 'length_less' => 13],
            ['flute_code' => 'EFC', 'compute' => false, 'length_add' => 21, 'length_less' => 10],
            ['flute_code' => 'EFC', 'compute' => true, 'length_add' => 20, 'length_less' => 10],
            ['flute_code' => 'EFS', 'compute' => false, 'length_add' => 29, 'length_less' => 17],
            ['flute_code' => 'EFS', 'compute' => true, 'length_add' => 32, 'length_less' => 11],
            ['flute_code' => 'ES', 'compute' => false, 'length_add' => 25, 'length_less' => 6],
            ['flute_code' => 'ES', 'compute' => true, 'length_add' => 20, 'length_less' => 21],
            ['flute_code' => 'ETC', 'compute' => false, 'length_add' => 14, 'length_less' => 9],
            ['flute_code' => 'ETC', 'compute' => true, 'length_add' => 30, 'length_less' => 25],
            ['flute_code' => 'OF', 'compute' => false, 'length_add' => 12, 'length_less' => 5],
            ['flute_code' => 'OF', 'compute' => true, 'length_add' => 38, 'length_less' => 14],
            ['flute_code' => 'OFD', 'compute' => false, 'length_add' => 23, 'length_less' => 9],
            ['flute_code' => 'OFD', 'compute' => true, 'length_add' => 34, 'length_less' => 23],
            ['flute_code' => 'OFE', 'compute' => false, 'length_add' => 15, 'length_less' => 20],
            ['flute_code' => 'OFE', 'compute' => true, 'length_add' => 40, 'length_less' => 12],
            ['flute_code' => 'RL', 'compute' => false, 'length_add' => 20, 'length_less' => 8],
            ['flute_code' => 'RL', 'compute' => true, 'length_add' => 21, 'length_less' => 11],
        ];

        $count = 0;
        foreach ($sideTrimData as $data) {
            // Find the flute by code
            $flute = PaperFlute::where('code', $data['flute_code'])->first();
            
            if (!$flute) {
                $this->command->warn("Flute with code {$data['flute_code']} not found. Skipping.");
                continue;
            }

            // Create or update the side trim entry
            SideTrimByFlute::updateOrCreate(
                [
                    'flute_id' => $flute->id,
                    'compute' => $data['compute'],
                ],
                [
                    'length_add' => $data['length_add'],
                    'length_less' => $data['length_less'],
                ]
            );
            $count++;
        }
        
        $this->command->info("Created $count side trim by flute records.");
    }
} 