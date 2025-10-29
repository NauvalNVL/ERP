<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncMcApprovalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:sync-approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync MC approval status from approve_mcs table to MC table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting MC approval status sync...');
        
        try {
            // Update MC table with approval status from approve_mcs table
            $updated = DB::statement("
                UPDATE MC 
                SET mc_approval = 'Yes' 
                WHERE MCS_Num IN (
                    SELECT mc_seq 
                    FROM approve_mcs 
                    WHERE status = 'active'
                )
            ");
            
            $this->info('✓ Successfully synced MC approval status');
            
            // Count how many records were affected
            $count = DB::select("
                SELECT COUNT(*) as count 
                FROM MC 
                WHERE mc_approval = 'Yes'
            ");
            
            $this->info("Total approved MCs in MC table: " . $count[0]->count);
            
            Log::info('MC approval status synced successfully', ['count' => $count[0]->count]);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to sync MC approval status: ' . $e->getMessage());
            Log::error('Failed to sync MC approval status', ['error' => $e->getMessage()]);
            return 1;
        }
    }
}
