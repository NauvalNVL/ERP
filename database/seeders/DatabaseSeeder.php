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
            CustomerSalesTypeSeeder::class,
            AnalysisCodesTableSeeder::class,
            CustomerSeeder::class,
            CustomerAlternateAddressSeeder::class,
            UserCpsSeeder::class,
            FinishingSeeder::class,
            StitchWireSeeder::class,
            ChemicalCoatSeeder::class,
            PaperFluteSeeder::class,
            ProductSeeder::class,
            SalesTeamSeeder::class,
            SalespersonSeeder::class,
            SalespersonTeamSeeder::class,
            ScoringToolSeeder::class,
            PaperQualitySeeder::class,
            PaperSizeSeeder::class,
            ColorGroupSeeder::class,
            ColorSeeder::class,
            IndustrySeeder::class,
            GeoSeeder::class,
            ProductGroupSeeder::class,
            ProductDesignSeeder::class,
            VehicleClassSeeder::class,
            VehicleSeeder::class,
            McTableSeeder::class,
        ]);
    }
}
