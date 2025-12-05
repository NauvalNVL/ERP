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
            // Master data that CUSTOMER depends on
            SalespersonSeeder::class,
            IndustrySeeder::class,
            GeoSeeder::class,
            CustGroupSeeder::class,

            // Customer and related
            CustomerSeeder::class,
            CustomerAlternateAddressSeeder::class,

            // Other masters and transactional/setup data
            UserCpsSeeder::class,
            FinishingSeeder::class,
            StitchWireSeeder::class,
            ChemicalCoatSeeder::class,
            PaperFluteSeeder::class,
            ProductSeeder::class,
            ScoringToolSeeder::class,
            PaperQualitySeeder::class,
            PaperSizeSeeder::class,
            ColorGroupSeeder::class,
            ColorSeeder::class,
            ProductGroupSeeder::class,
            ProductDesignSeeder::class,
            VehicleClassSeeder::class,
            VehicleSeeder::class,
            MachineSeeder::class,
            // McTableSeeder::class,
        ]);
    }
}
