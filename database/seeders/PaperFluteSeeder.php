<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaperFlute;

class PaperFluteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flutes = [
            ['code' => 'A', 'name' => 'A FLUTE'],
            ['code' => 'AF', 'name' => 'A FLUTE'],
            ['code' => 'BC', 'name' => 'BC'],
            ['code' => 'BF2', 'name' => 'BF2 FLUTE'],
            ['code' => 'BF', 'name' => 'BF FLUTE'],
            ['code' => 'B', 'name' => 'B FLUTE'],
            ['code' => 'BFS', 'name' => 'BF SINGLE FACER'],
            ['code' => 'BD', 'name' => 'BHPT FINAL'],
            ['code' => 'CF', 'name' => 'CF'],
            ['code' => 'CF2', 'name' => 'C FLUTE 2'],
            ['code' => 'CR', 'name' => 'CONEJIT'],
            ['code' => 'DF', 'name' => 'DF'],
            ['code' => 'E', 'name' => 'E'],
            ['code' => 'EF', 'name' => 'E FLUTE'],
            ['code' => 'EFC', 'name' => 'EF SINGLE FACER'],
            ['code' => 'EFS', 'name' => 'EF SINGLE FACER'],
            ['code' => 'ES', 'name' => 'E FLUTE SINGLE FACER'],
            ['code' => 'ETC', 'name' => 'LAIN LAIN'],
            ['code' => 'OF', 'name' => 'OFFSET CF'],
            ['code' => 'OFD', 'name' => 'OFFSET DOUBLE DUPLX'],
            ['code' => 'OFE', 'name' => 'OFFSET E FLUTE'],
            ['code' => 'RL', 'name' => 'ROLL'],
        ];

        foreach ($flutes as $flute) {
            PaperFlute::updateOrCreate(
                ['code' => $flute['code']],
                ['name' => $flute['name']]
            );
        }
    }
}
