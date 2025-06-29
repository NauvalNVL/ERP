<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\CorrugatorSpecByProduct;
use Illuminate\Support\Facades\DB;

class CorrugatorSpecByProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is intentionally left blank to prevent accidental data overwrites.
        // The data for corrugator specifications is now managed solely through the application's user interface.
        $this->command->info('CorrugatorSpecByProductSeeder intentionally skipped.');
    }
} 