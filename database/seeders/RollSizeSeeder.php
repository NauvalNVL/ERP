<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RollSize;
use App\Models\PaperFlute;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RollSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure the 'compute' column exists and is of the correct type before seeding
        if (!Schema::hasColumn('roll_sizes', 'compute')) {
            if (Schema::hasColumn('roll_sizes', 'is_composite')) {
                // Rename 'is_composite' to 'compute'
                Schema::table('roll_sizes', function ($table) {
                    $table->renameColumn('is_composite', 'compute');
                });
            } else {
                // Add the 'compute' column if it doesn't exist
                Schema::table('roll_sizes', function ($table) {
                    $table->boolean('compute')->default(false);
                });
            }
        }
        
        // Ensure the column is of type 'bit' for SQL Server.
        // The column name 'compute' is a reserved keyword and must be escaped.
        // The DEFAULT clause is handled by the Schema builder when the column is created.
        DB::statement("ALTER TABLE roll_sizes ALTER COLUMN [compute] bit NOT NULL");

        // Truncate the table to start fresh
        // RollSize::truncate(); // Disabling truncate to preserve existing data if any

        $flutes = PaperFlute::all();
        $rollSizesData = [
            'B' => [1000, 1100, 1200, 1300, 1400, 1500, 1600, 1700, 1800, 1900, 2000, 2100, 2200, 2300, 2400, 2500],
            'C' => [1000, 1100, 1200, 1300, 1400, 1500, 1600, 1700, 1800, 1900, 2000, 2100, 2200, 2300, 2400, 2500],
            'E' => [1000, 1100, 1200, 1300, 1400, 1500, 1600, 1700, 1800, 1900, 2000, 2100, 2200, 2300, 2400, 2500],
            'BC' => [1200, 1400, 1600, 1800, 2000, 2200, 2400],
            'BE' => [1200, 1400, 1600, 1800, 2000, 2200, 2400],
        ];

        foreach ($flutes as $flute) {
            if (isset($rollSizesData[$flute->code])) {
                foreach ($rollSizesData[$flute->code] as $length) {
                    RollSize::updateOrCreate(
                        ['flute_id' => $flute->id, 'roll_length' => $length],
                        ['compute' => true] 
                    );
                }
            }
        }
    }
} 