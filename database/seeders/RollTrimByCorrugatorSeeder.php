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
        
        // Sample corrugator names
        $corrugators = ['BHS', 'FOSBER', 'MHI'];
        
        // Create roll trim data for each flute and corrugator combination
        foreach ($paperFlutes as $flute) {
            foreach ($corrugators as $corrugator) {
                // Generate random trim values between 5 and 15 cm
                $trimValue = rand(5, 15);
                
                RollTrimByCorrugator::updateOrCreate(
                    [
                        'corrugator_name' => $corrugator,
                        'flute_code' => $flute->code,
                    ],
                    [
                        'trim_value' => $trimValue,
                    ]
                );
            }
        }
    }
} 