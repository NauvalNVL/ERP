<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCps;

class TestAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test authentication and user model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Authentication System...');
        $this->newLine();
        
        // Test 1: Check if users exist
        $this->info('1. Checking users in database...');
        $userCount = UserCps::count();
        $this->info("   Found {$userCount} users in usercps table");
        
        if ($userCount > 0) {
            $firstUser = UserCps::first();
            $this->info("   First user: {$firstUser->userName} (ID: {$firstUser->userID})");
            $this->newLine();
            
            // Test 2: Check user attributes
            $this->info('2. User attributes:');
            $attributes = $firstUser->getAttributes();
            foreach ($attributes as $key => $value) {
                $this->line("   - {$key}: " . ($value ?? 'null'));
            }
        }
        
        $this->newLine();
        $this->info('✅ Test completed!');
        $this->info('Now try to login and perform Obsolete/Reactive operation.');
        $this->info('Check storage/logs/laravel.log for detailed authentication logs.');
        
        return Command::SUCCESS;
    }
}
