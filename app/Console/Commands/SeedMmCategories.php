<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\MmCategorySeeder;

class SeedMmCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:mm-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the material management categories table with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to seed Material Management Categories...');
        
        $seeder = new MmCategorySeeder();
        $seeder->run();
        
        $this->info('Material Management Categories seeded successfully!');
        
        return Command::SUCCESS;
    }
}
