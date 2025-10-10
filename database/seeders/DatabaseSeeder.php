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
            PurchaseSubControlSeeder::class,
            ApproverSeeder::class,
            PurchaserSeeder::class,
            PurchaserApprovalFlowSeeder::class,
            MmSkuSeeder::class,
            SkuPriceSeeder::class,
            SkuTypeSeeder::class,
            UnlockSkuUtilitySeeder::class,
            DrCrNoteSeeder::class,
            SkuCustomTariffCodeSeeder::class,
            CustomTariffCodeSeeder::class,
            SkuConsumptionBudgetSeeder::class,
            SkuReorderLevelSeeder::class,
            ChartOfAccountSeeder::class,
            CustomerSalesTypeSeeder::class,
            MmGlDistributionSeeder::class,
            MmReportGroupSeeder::class,
            MmUnitSeeder::class,
            DeliveryOrderFormatSeeder::class,
            CustomerSeeder::class,
            CustomerAlternateAddressSeeder::class,
            ForeignCurrencySeeder::class,
            MmLocationSeeder::class,
            CorrugatorSpecByProductSeeder::class,
            ProductDesignSeeder::class,
            RollSizeSeeder::class,
            RollTrimByProductDesignSeeder::class,
            RollTrimByCorrugatorSeeder::class,
            SideTrimByFluteSeeder::class,
            SideTrimByProductDesignSeeder::class,
            WarehouseLocationSeeder::class,
            MmCategorySeeder::class,
            MmAnalysisCodeSeeder::class,
            MmReceiveDestinationSeeder::class,
            MmTaxGroupSeeder::class,
            MmTaxTypeSeeder::class,
            MmTransactionTypeSeeder::class,
            MmControlPeriodSeeder::class,
            MmConfigSeeder::class,
            ComputationFormulaSeeder::class,
            BundlingComputationMethodSeeder::class,
            ObsolateReactiveMCSeeder::class,
            BusinessFormSeeder::class,
            UserSeeder::class,
            FinishingSeeder::class,
            ComputationMethodSeeder::class,
            PaperFluteSeeder::class,
            ProductSeeder::class,
            CorrugatorConfigSeeder::class,
            ScoringFormulaSeeder::class,
            StandardFormulaSeeder::class,
            // UpdateMcSeeder::class, // disabled per request
            MasterCardSeeder::class,
            VendorSeeder::class,
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
            VehicleClassSeeder::class,
            VehicleSeeder::class,
            McTableSeeder::class,
        ]);
    }
}
