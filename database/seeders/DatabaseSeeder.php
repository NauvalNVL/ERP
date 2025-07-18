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
            UserSeeder::class,
            ChartOfAccountSeeder::class,
            ApproveMCSeeder::class,
            BundlingComputationMethodSeeder::class,
            BusinessFormSeeder::class,
            ColorGroupSeeder::class,
            ColorSeeder::class,
            ComputationFormulaSeeder::class,
            ComputationMethodSeeder::class,
            CorrugatorConfigSeeder::class,
            CorrugatorSpecByProductSeeder::class,
            CustomerAlternateAddressSeeder::class,
            CustomerGroupSeeder::class,
            CustomerSalesTypeSeeder::class,
            DeliveryOrderFormatSeeder::class,
            FinishingSeeder::class,
            ForeignCurrencySeeder::class,
            GeoSeeder::class,
            IndustrySeeder::class,
            MmAnalysisCodeSeeder::class,
            MmCategorySeeder::class,
            MmConfigSeeder::class,
            MmControlPeriodSeeder::class,
            MmLocationSeeder::class,
            MmReceiveDestinationSeeder::class,
            MmReportGroupSeeder::class, // Added MmReportGroupSeeder
            MmGlDistributionSeeder::class,
            MmSkuSeeder::class,
            MmTaxGroupSeeder::class,
            MmTaxTypeSeeder::class,
            MmTransactionTypeSeeder::class,
            MmUnitSeeder::class,
            ObsolateReactiveMCSeeder::class,
            PaperFluteSeeder::class,
            PaperQualitySeeder::class,
            PaperSizeSeeder::class,
            ProductDesignSeeder::class,
            ProductGroupSeeder::class,
            ProductSeeder::class,
            RollSizeSeeder::class,
            RollTrimByCorrugatorSeeder::class,
            RollTrimByProductDesignSeeder::class,
            SalesTeamSeeder::class,
            SalespersonSeeder::class,
            SalespersonTeamSeeder::class,
            ScoringFormulaSeeder::class,
            ScoringToolSeeder::class,
            SideTrimByFluteSeeder::class,
            SideTrimByProductDesignSeeder::class,
            StandardFormulaSeeder::class,
            UpdateCustomerAccountSeeder::class,
            WarehouseLocationSeeder::class,
        ]);
    }
}
