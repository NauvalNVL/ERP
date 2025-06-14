<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComputationMethod;

class ComputationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default computation method if none exists
        if (ComputationMethod::count() === 0) {
            ComputationMethod::create([
                'formula_divisor' => 1,
                'formula_pieces' => 0,
                'height_type' => 'internal',
                'rounding_type' => 'down',
                'is_active' => true,
                'created_by' => 'System Seeder',
            ]);
            
            $this->command->info('Default computation method created successfully.');
        } else {
            $this->command->info('Computation methods already exist. No seeding required.');
        }
    }
} 