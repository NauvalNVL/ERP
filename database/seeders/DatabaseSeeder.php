<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\MmUnitSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            MmConfigSeeder::class,
            ApproveMCSeeder::class,
            BusinessFormSeeder::class,
            BundlingComputationMethodSeeder::class,
            ColorGroupSeeder::class,
            ColorSeeder::class,
            ComputationFormulaSeeder::class,
            ComputationMethodSeeder::class,
            CorrugatorConfigSeeder::class,
            CorrugatorSpecByProductSeeder::class,
            FinishingSeeder::class,
            ForeignCurrencySeeder::class,
            GeoSeeder::class,
            IndustrySeeder::class,
            MmAnalysisCodeSeeder::class,
            MmCategorySeeder::class,
            MmLocationSeeder::class,
            MmReceiveDestinationSeeder::class,
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
            SalespersonSeeder::class,
            SalespersonTeamSeeder::class,
            SalesTeamSeeder::class,
            ScoringFormulaSeeder::class,
            ScoringToolSeeder::class,
            SideTrimByFluteSeeder::class,
            SideTrimByProductDesignSeeder::class,
            StandardFormulaSeeder::class,
            UserSeeder::class,
            WarehouseLocationSeeder::class
        ]);

        $this->call(MmControlPeriodSeeder::class);
        $this->call(MmTransactionTypeSeeder::class);
        $this->call(MmTaxTypeSeeder::class);
        $this->call(MmTaxGroupSeeder::class);
    }
}
