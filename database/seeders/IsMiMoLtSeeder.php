<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IsMiMoLt;
use App\Models\MmSku;
use App\Models\MmLocation;
use App\Models\MmCategory;
use Carbon\Carbon;

class IsMiMoLtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample data from existing tables
        $skus = MmSku::where('is_active', true)->limit(10)->get();
        $locations = MmLocation::where('is_active', true)->limit(5)->get();
        $categories = MmCategory::where('is_active', true)->limit(5)->get();

        if ($skus->isEmpty() || $locations->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        $transactionTypes = ['IS', 'MI', 'MO', 'LT'];
        $statuses = ['Draft', 'Posted', 'Cancelled'];
        $descriptions = [
            'Initial stock setup',
            'Material issue for production',
            'Material order for replenishment',
            'Location transfer for better organization',
            'Stock adjustment due to physical count',
            'Material issue for maintenance',
            'Emergency material order',
            'Inter-location transfer',
            'Stock correction entry',
            'Material issue for quality control'
        ];

        // Create sample transactions
        for ($i = 0; $i < 50; $i++) {
            $type = $transactionTypes[array_rand($transactionTypes)];
            $status = $statuses[array_rand($statuses)];
            $sku = $skus->random();
            $location = $locations->random();
            $category = $categories->random();
            
            $quantity = rand(10, 1000);
            $unitPrice = rand(100, 50000) / 100;
            $totalAmount = $quantity * $unitPrice;
            
            $transactionDate = Carbon::now()->subDays(rand(0, 30));
            
            $transaction = IsMiMoLt::create([
                'transaction_number' => $type . date('Y') . date('m') . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '-' . time(),
                'transaction_type' => $type,
                'sku_code' => $sku->sku,
                'location_code' => $location->code,
                'category_code' => $category->code,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'description' => $descriptions[array_rand($descriptions)],
                'transaction_date' => $transactionDate,
                'reference_number' => 'REF-' . strtoupper(substr(md5(rand()), 0, 8)),
                'notes' => rand(0, 1) ? 'Sample transaction for testing purposes' : null,
                'status' => $status,
                'created_by' => 1,
                'updated_by' => rand(0, 1) ? 1 : null,
                'posted_by' => $status === 'Posted' ? 1 : null,
                'posted_at' => $status === 'Posted' ? $transactionDate->addHours(rand(1, 8)) : null,
                'cancelled_by' => $status === 'Cancelled' ? 1 : null,
                'cancelled_at' => $status === 'Cancelled' ? $transactionDate->addHours(rand(1, 8)) : null,
                'cancellation_reason' => $status === 'Cancelled' ? 'Sample cancellation for testing' : null
            ]);
        }

        $this->command->info('IS/MI/MO/LT sample data created successfully!');
    }
}
