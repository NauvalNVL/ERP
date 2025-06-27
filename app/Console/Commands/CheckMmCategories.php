<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MmCategory;

class CheckMmCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:mm-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and display all Material Management Categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categories = MmCategory::all();
        
        if ($categories->isEmpty()) {
            $this->error('No categories found in the database.');
            return Command::FAILURE;
        }
        
        $this->info('Found ' . $categories->count() . ' categories:');
        $this->newLine();
        
        foreach ($categories as $category) {
            $this->line(
                $category->code . ' - ' . 
                $category->name . ' - ' .
                ($category->is_active ? 'Active' : 'Inactive')
            );
        }
        
        $this->newLine();
        $this->info('Seeding was successful!');
        
        return Command::SUCCESS;
    }
} 