<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkuConsumptionBudget;
use App\Models\MmSku;
use Carbon\Carbon;

class SkuConsumptionBudgetSeeder extends Seeder
{
    public function run()
    {
        // Get some sample SKUs
        $skus = MmSku::where('is_active', true)->limit(10)->get();
        
        if ($skus->isEmpty()) {
            $this->command->info('No active SKUs found. Skipping consumption budget seeding.');
            return;
        }

        $now = Carbon::now();
        $budgets = [];

        foreach ($skus as $sku) {
            // Create budget data for the next 12 months
            for ($i = 0; $i < 12; $i++) {
                $date = $now->copy()->addMonths($i);
                $effectiveMonth = $date->format('m/Y');
                
                // Generate realistic budget quantities based on SKU type
                $baseQuantity = rand(100, 1000);
                $budgetQuantity = $baseQuantity + rand(-50, 100);
                $actualQuantity = $budgetQuantity + rand(-100, 50);
                
                $budgets[] = [
                    'sku_id' => $sku->sku,
                    'effective_month' => $effectiveMonth,
                    'budget_quantity' => $budgetQuantity,
                    'actual_quantity' => $actualQuantity,
                    'variance' => $budgetQuantity - $actualQuantity,
                    'notes' => 'Sample budget data for testing',
                    'created_by' => 'seeder',
                    'updated_by' => 'seeder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($budgets, 100) as $chunk) {
            SkuConsumptionBudget::insert($chunk);
        }

        $this->command->info('SKU Consumption Budget data seeded successfully!');
    }
} 