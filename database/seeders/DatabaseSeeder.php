<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SalesTeamSeeder::class,
            SalespersonSeeder::class,
            ProductGroupSeeder::class,
            GeoSeeder::class,
            PaperSizeSeeder::class,
            PaperFluteSeeder::class,
            PaperQualitySeeder::class,
            ScoringToolSeeder::class,
            UserSeeder::class,
            ColorSeeder::class,
            ColorGroupSeeder::class,
            ProductSeeder::class,
            FinishingSeeder::class,
        ]);
    }
}
