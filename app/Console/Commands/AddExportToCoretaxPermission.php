<?php

namespace App\Console\Commands;

use App\Models\UserCps;
use App\Models\UserPermission;
use Illuminate\Console\Command;

class AddExportToCoretaxPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:add-export-coretax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Export to Coretax permission to all existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding Export to Coretax permission to all users...');

        $users = UserCps::all();
        $addedCount = 0;
        $skippedCount = 0;

        foreach ($users as $user) {
            // Check if permission already exists
            $exists = UserPermission::where('user_id', $user->userID)
                ->where('menu_key', 'export_to_coretax')
                ->exists();

            if ($exists) {
                $this->warn("Permission already exists for user: {$user->userID} ({$user->username})");
                $skippedCount++;
                continue;
            }

            // Create new permission
            UserPermission::create([
                'user_id' => $user->userID,
                'menu_key' => 'export_to_coretax',
                'menu_name' => 'Export to Coretax',
                'menu_route' => '/warehouse-management/invoice/tax-djp/export-to-coretax',
                'menu_category' => 'warehouse_management',
                'menu_parent' => 'warehouse_management',
                'can_access' => true
            ]);

            $this->info("✓ Added permission for user: {$user->userID} ({$user->username})");
            $addedCount++;
        }

        $this->newLine();
        $this->info("===========================================");
        $this->info("Summary:");
        $this->info("Total users processed: " . $users->count());
        $this->info("Permissions added: {$addedCount}");
        $this->info("Skipped (already exists): {$skippedCount}");
        $this->info("===========================================");
        $this->newLine();
        $this->info('✓ Export to Coretax permission has been added successfully!');

        return 0;
    }
}
