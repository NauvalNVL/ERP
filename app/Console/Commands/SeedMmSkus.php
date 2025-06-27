<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\MmSkuSeeder;

class SeedMmSkus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:mm-skus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the material management SKUs table with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to seed Material Management SKUs...');
        
        $seeder = new MmSkuSeeder();
        $seeder->run();
        
        $this->info('Material Management SKUs seeded successfully!');
        
        return Command::SUCCESS;
    }
} 