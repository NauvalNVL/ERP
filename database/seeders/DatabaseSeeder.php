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
            MmConfigSeeder::class,
            SalesTeamSeeder::class,
            SalespersonSeeder::class,
            SalespersonTeamSeeder::class,
            ProductGroupSeeder::class,
            GeoSeeder::class,
            PaperSizeSeeder::class,
            PaperFluteSeeder::class,
            PaperQualitySeeder::class,
            ScoringToolSeeder::class,
            UserSeeder::class,
            // ColorSeeder::class, // Temporarily commented out due to syntax error
            ColorGroupSeeder::class,
            ProductSeeder::class,
            FinishingSeeder::class,
            BusinessFormSeeder::class,
            ProductDesignSeeder::class,
            IndustrySeeder::class,
            CustomerGroupSeeder::class,
            UpdateCustomerAccountSeeder::class,
            CustomerAlternateAddressSeeder::class,
            ApproveMCSeeder::class,
            ObsolateReactiveMCSeeder::class,
            StandardFormulaSeeder::class,
            ScoringFormulaSeeder::class,
            CustomerSalesTypeSeeder::class,
            CorrugatorConfigSeeder::class,
            CorrugatorSpecByProductSeeder::class,
            BundlingComputationMethodSeeder::class,
            MmReceiveDestinationSeeder::class,
            MmAnalysisCodeSeeder::class,
            MmLocationSeeder::class,
            WarehouseLocationSeeder::class,
            MmCategorySeeder::class,
            MmSkuSeeder::class,
            SideTrimByFluteSeeder::class,
            RollTrimByCorrugatorSeeder::class,
            RollTrimByProductDesignSeeder::class,
            RollSizeSeeder::class,
            SideTrimByProductDesignSeeder::class,
            ComputationMethodSeeder::class,
        ]);

        $this->call(MmControlPeriodSeeder::class);
        $this->call(MmTransactionTypeSeeder::class);
        $this->call(MmTaxTypeSeeder::class);
        $this->call(MmTaxGroupSeeder::class);
    }
}
