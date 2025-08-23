<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RcRt;
use App\Models\Vendor;
use App\Models\MmSku;
use App\Models\MmLocation;
use Carbon\Carbon;

class RcRtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample data
        $vendors = Vendor::where('is_active', true)->limit(5)->get();
        $skus = MmSku::where('is_active', true)->limit(10)->get();
        $locations = MmLocation::where('is_active', true)->limit(3)->get();

        if ($vendors->isEmpty() || $skus->isEmpty() || $locations->isEmpty()) {
            $this->command->info('Missing required data. Please ensure Vendor, MmSku, and MmLocation models have data.');
            return;
        }

        $statuses = ['Draft', 'Posted', 'Cancelled'];
        $transactionTypes = ['RC', 'RT'];

        // Generate sample RC transactions
        for ($i = 1; $i <= 20; $i++) {
            $vendor = $vendors->random();
            $sku = $skus->random();
            $location = $locations->random();
            $status = $statuses[array_rand($statuses)];
            $transactionType = 'RC';

            $year = date('Y');
            $month = date('m');
            $transactionNumber = $transactionType . $year . $month . str_pad($i, 4, '0', STR_PAD_LEFT);

            $quantity = rand(10, 1000);
            $unitPrice = rand(10000, 500000);
            $totalAmount = $quantity * $unitPrice;

            $transaction = RcRt::create([
                'transaction_number' => $transactionNumber,
                'transaction_type' => $transactionType,
                'transaction_date' => Carbon::now()->subDays(rand(1, 30)),
                'supplier_code' => $vendor->ap_ac_number,
                'supplier_name' => $vendor->vendor_name,
                'po_number' => 'PO-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'sku_code' => $sku->sku,
                'sku_name' => $sku->sku_name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'location_code' => $location->code,
                'location_name' => $location->name,
                'status' => $status,
                'remarks' => 'Sample RC transaction ' . $i,
                'created_by' => 'admin',
                'approved_by' => $status === 'Posted' ? 'admin' : null,
                'approved_at' => $status === 'Posted' ? Carbon::now() : null,
                'posted_by' => $status === 'Posted' ? 'admin' : null,
                'posted_at' => $status === 'Posted' ? Carbon::now() : null,
                'cancelled_by' => $status === 'Cancelled' ? 'admin' : null,
                'cancelled_at' => $status === 'Cancelled' ? Carbon::now() : null,
                'cancellation_reason' => $status === 'Cancelled' ? 'Sample cancellation reason' : null,
            ]);
        }

        // Generate sample RT transactions
        for ($i = 1; $i <= 15; $i++) {
            $vendor = $vendors->random();
            $sku = $skus->random();
            $location = $locations->random();
            $status = $statuses[array_rand($statuses)];
            $transactionType = 'RT';

            $year = date('Y');
            $month = date('m');
            $transactionNumber = $transactionType . $year . $month . str_pad($i, 4, '0', STR_PAD_LEFT);

            $quantity = rand(5, 500);
            $unitPrice = rand(10000, 500000);
            $totalAmount = $quantity * $unitPrice;

            $transaction = RcRt::create([
                'transaction_number' => $transactionNumber,
                'transaction_type' => $transactionType,
                'transaction_date' => Carbon::now()->subDays(rand(1, 30)),
                'supplier_code' => $vendor->ap_ac_number,
                'supplier_name' => $vendor->vendor_name,
                'po_number' => 'PO-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'sku_code' => $sku->sku,
                'sku_name' => $sku->sku_name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'location_code' => $location->code,
                'location_name' => $location->name,
                'status' => $status,
                'remarks' => 'Sample RT transaction ' . $i,
                'created_by' => 'admin',
                'approved_by' => $status === 'Posted' ? 'admin' : null,
                'approved_at' => $status === 'Posted' ? Carbon::now() : null,
                'posted_by' => $status === 'Posted' ? 'admin' : null,
                'posted_at' => $status === 'Posted' ? Carbon::now() : null,
                'cancelled_by' => $status === 'Cancelled' ? 'admin' : null,
                'cancelled_at' => $status === 'Cancelled' ? Carbon::now() : null,
                'cancellation_reason' => $status === 'Cancelled' ? 'Sample cancellation reason' : null,
            ]);
        }

        $this->command->info('RC/RT sample data seeded successfully!');
        $this->command->info('Created 20 RC transactions and 15 RT transactions');
    }
}
