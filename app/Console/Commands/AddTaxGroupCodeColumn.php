<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTaxGroupCodeColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax:add-group-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add tax_group_code column to tax_types table if it doesn\'t exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking tax_types table for tax_group_code column...');
        
        if (!Schema::hasColumn('tax_types', 'tax_group_code')) {
            $this->info('Column not found. Adding tax_group_code column...');
            
            try {
                Schema::table('tax_types', function ($table) {
                    $table->string('tax_group_code', 20)->nullable()->after('custom_type');
                });
                
                $this->info('✅ Column tax_group_code added successfully!');
                return 0;
            } catch (\Exception $e) {
                $this->error('❌ Error adding column: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('✅ Column tax_group_code already exists!');
            return 0;
        }
    }
}
