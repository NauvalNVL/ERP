<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MmSku;
use Carbon\Carbon;

class UnlockSkuUtilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing SKUs to lock
        $skus = MmSku::take(5)->get();
        
        if ($skus->isEmpty()) {
            $this->command->info('No SKUs found to lock. Please run MmSkuSeeder first.');
            return;
        }

        $lockReasons = [
            'Transaction in progress - Sales Order processing',
            'System failure during update - Recovery needed',
            'User session timeout - Manual unlock required',
            'Data validation error - Pending correction',
            'Batch processing lock - Awaiting completion'
        ];

        $users = ['John Doe', 'Jane Smith', 'Admin User', 'System Process', 'Data Manager'];

        foreach ($skus as $index => $sku) {
            // Randomly decide if this SKU should be locked
            if (rand(0, 1)) {
                $lockReason = $lockReasons[$index % count($lockReasons)];
                $lockedBy = $users[$index % count($users)];
                
                // Create different lock scenarios
                $lockScenario = $index % 3;
                
                switch ($lockScenario) {
                    case 0: // Recent lock (within 30 minutes)
                        $lockedAt = Carbon::now()->subMinutes(rand(5, 25));
                        break;
                    case 1: // Stale lock (older than 30 minutes)
                        $lockedAt = Carbon::now()->subMinutes(rand(35, 120));
                        break;
                    case 2: // Very old lock (older than 1 hour)
                        $lockedAt = Carbon::now()->subHours(rand(2, 6));
                        break;
                }

                $sku->update([
                    'is_locked' => true,
                    'locked_by' => $lockedBy,
                    'locked_at' => $lockedAt,
                    'lock_reason' => $lockReason,
                    'lock_session_id' => 'session_' . uniqid()
                ]);

                $this->command->info("Locked SKU: {$sku->sku} - {$sku->sku_name}");
            }
        }

        $this->command->info('Unlock SKU Utility seeder completed successfully!');
    }
} 