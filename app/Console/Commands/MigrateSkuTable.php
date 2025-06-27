<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateSkuTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:sku-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the migration for the SKU table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running migration for the SKU table...');
        
        Artisan::call('migrate --path=database/migrations/2025_08_04_000000_create_mm_skus_table.php');
        
        $this->info('Migration for SKU table completed successfully!');
        
        return Command::SUCCESS;
    }
} 