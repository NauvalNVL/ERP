<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\McTableSeeder;

class SeedMcTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:mc-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the MC table with master card data following the update MC menu structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting MC table seeding...');
        
        try {
            $seeder = new McTableSeeder();
            $seeder->run();
            
            $this->info('MC table seeding completed successfully!');
        } catch (\Exception $e) {
            $this->error('Error seeding MC table: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
