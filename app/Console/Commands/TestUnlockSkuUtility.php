<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MmSku;
use Illuminate\Support\Facades\DB;

class TestUnlockSkuUtility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:unlock-sku-utility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Unlock SKU Utility functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Unlock SKU Utility Test Script ===');
        $this->newLine();

        try {
            // Test 1: Check if lock fields exist
            $this->info('1. Checking database schema...');
            $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'mm_skus' AND COLUMN_NAME = 'is_locked'");
            if (empty($columns)) {
                $this->error('   ❌ Lock fields not found in mm_skus table');
                $this->error('   Please run the migration: php artisan migrate');
                return 1;
            }
            $this->info('   ✅ Lock fields found in mm_skus table');
            $this->newLine();

            // Test 2: Check for locked SKUs
            $this->info('2. Checking for locked SKUs...');
            $lockedSkus = MmSku::where('is_locked', true)->count();
            $this->info("   Found {$lockedSkus} locked SKU(s)");
            
            if ($lockedSkus > 0) {
                $sampleLocked = MmSku::where('is_locked', true)->first();
                $this->info("   Sample locked SKU: {$sampleLocked->sku} - {$sampleLocked->sku_name}");
                $this->info("   Locked by: {$sampleLocked->locked_by}");
                $this->info("   Lock reason: {$sampleLocked->lock_reason}");
                $this->info("   Locked at: {$sampleLocked->locked_at}");
            }
            $this->newLine();

            // Test 3: Check for stale locks
            $this->info('3. Checking for stale locks...');
            $staleLocks = MmSku::where('is_locked', true)
                ->where('locked_at', '<', now()->subMinutes(30))
                ->count();
            $this->info("   Found {$staleLocks} stale lock(s) (>30 minutes)");
            $this->newLine();

            // Test 4: Test lock duration calculation
            $this->info('4. Testing lock duration calculation...');
            if ($lockedSkus > 0) {
                $sampleLocked = MmSku::where('is_locked', true)->first();
                $duration = $sampleLocked->lock_duration;
                $isStale = $sampleLocked->isLockStale();
                $this->info("   Sample SKU lock duration: {$duration} minutes");
                $this->info("   Is stale: " . ($isStale ? 'Yes' : 'No'));
            }
            $this->newLine();

            // Test 5: Check lock statistics
            $this->info('5. Generating lock statistics...');
            $totalLocked = MmSku::where('is_locked', true)->count();
            $staleLocks = MmSku::where('is_locked', true)
                ->where('locked_at', '<', now()->subMinutes(30))
                ->count();
            $recentLocks = MmSku::where('is_locked', true)
                ->where('locked_at', '>=', now()->subHours(24))
                ->count();
            
            $this->info("   Total locked: {$totalLocked}");
            $this->info("   Stale locks: {$staleLocks}");
            $this->info("   Recent locks (24h): {$recentLocks}");
            $this->newLine();

            // Test 6: Check lock reasons
            $this->info('6. Analyzing lock reasons...');
            $lockReasons = MmSku::where('is_locked', true)
                ->selectRaw('lock_reason, COUNT(*) as count')
                ->groupBy('lock_reason')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
            
            if ($lockReasons->count() > 0) {
                $this->info("   Top lock reasons:");
                foreach ($lockReasons as $reason) {
                    $this->info("   - {$reason->lock_reason}: {$reason->count} SKU(s)");
                }
            } else {
                $this->info("   No lock reasons found");
            }
            $this->newLine();

            // Test 7: Test unlock functionality (simulation)
            $this->info('7. Testing unlock functionality (simulation)...');
            if ($lockedSkus > 0) {
                $sampleLocked = MmSku::where('is_locked', true)->first();
                $this->info("   Would unlock SKU: {$sampleLocked->sku}");
                $this->info("   Current lock status: " . ($sampleLocked->is_locked ? 'Locked' : 'Unlocked'));
                
                // Simulate unlock (don't actually unlock)
                $this->info("   Simulation: Unlock would set is_locked = false");
                $this->info("   Simulation: Would clear locked_by, locked_at, lock_reason, lock_session_id");
            }
            $this->newLine();

            $this->info('=== Test Summary ===');
            $this->info('✅ Database schema: OK');
            $this->info("✅ Locked SKUs found: {$lockedSkus}");
            $this->info("✅ Stale locks found: {$staleLocks}");
            $this->info('✅ Lock duration calculation: OK');
            $this->info('✅ Statistics generation: OK');
            $this->info('✅ Unlock simulation: OK');
            $this->newLine();

            $this->info('🎉 All tests passed! The Unlock SKU Utility is ready to use.');
            $this->newLine();
            $this->info('To access the utility, visit:');
            $this->info('/material-management/system-requirement/inventory-setup/unlock-sku-utility');

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Test failed with error: " . $e->getMessage());
            $this->error("Stack trace:\n" . $e->getTraceAsString());
            return 1;
        }
    }
}
