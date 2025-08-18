<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SkuPrice;
use App\Models\MmSku;
use App\Models\ForeignCurrency;

class SkuPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing SKUs and currencies
        $skus = MmSku::take(10)->get();
        $currencies = ForeignCurrency::all();

        if ($skus->isEmpty()) {
            $this->command->warn('No SKUs found. Please run MmSkuSeeder first.');
            return;
        }

        if ($currencies->isEmpty()) {
            $this->command->warn('No currencies found. Please run ForeignCurrencySeeder first.');
            return;
        }

        $priceTypes = ['S', 'P']; // S-SKU, P-P/Order
        $poStatuses = ['0', 'P', 'C', 'M', 'X']; // Outstanding, Partial, Completed, Manual, Cancelled

        foreach ($skus as $sku) {
            // Create multiple price records for each SKU
            for ($i = 0; $i < rand(1, 3); $i++) {
                $currency = $currencies->random();
                $priceType = $priceTypes[array_rand($priceTypes)];
                $poStatus = $priceType === 'P' ? $poStatuses[array_rand($poStatuses)] : null;

                SkuPrice::create([
                    'sku_code' => $sku->sku,
                    'price' => rand(10000, 1000000) / 100, // Random price between 100 and 10000
                    'currency_code' => $currency->currency_code,
                    'effective_date' => now()->subDays(rand(0, 365))->format('Y-m-d'),
                    'is_active' => rand(0, 1),
                    'price_type' => $priceType,
                    'po_status' => $poStatus,
                    'notes' => rand(0, 1) ? 'Sample price data' : null,
                ]);
            }
        }

        $this->command->info('SKU Price data seeded successfully!');
    }
}
