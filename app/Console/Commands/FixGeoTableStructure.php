<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixGeoTableStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:fix-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix GEO table structure by removing id, created_at, updated_at columns';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting GEO table structure fix...');
        $this->info('');

        try {
            // Get current columns
            $this->info('Current table structure:');
            $columns = DB::select("
                SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = 'GEO'
                ORDER BY ORDINAL_POSITION
            ");
            
            foreach ($columns as $column) {
                $length = $column->CHARACTER_MAXIMUM_LENGTH ?? '';
                $this->line("  - {$column->COLUMN_NAME} ({$column->DATA_TYPE}{$length})");
            }
            
            $this->info('');
            
            // Check and drop 'id' column
            if ($this->columnExists('id')) {
                $this->warn('Dropping column: id');
                
                // First, find and drop the primary key constraint on id
                $constraint = DB::select("
                    SELECT name
                    FROM sys.key_constraints
                    WHERE type = 'PK' 
                    AND parent_object_id = OBJECT_ID('dbo.GEO')
                ");
                
                if (!empty($constraint)) {
                    $constraintName = $constraint[0]->name;
                    $this->line("  Dropping primary key constraint: {$constraintName}");
                    DB::statement("ALTER TABLE dbo.GEO DROP CONSTRAINT [{$constraintName}]");
                    $this->info("  ✓ Constraint {$constraintName} dropped");
                }
                
                // Now drop the id column
                DB::statement('ALTER TABLE dbo.GEO DROP COLUMN id');
                $this->info('✓ Column id dropped successfully');
                
                // Add PRIMARY KEY constraint to 'code' column
                $this->line('  Adding PRIMARY KEY to code column...');
                DB::statement('ALTER TABLE dbo.GEO ADD CONSTRAINT PK_GEO_CODE PRIMARY KEY (code)');
                $this->info('  ✓ PRIMARY KEY added to code column');
            } else {
                $this->line('✓ Column id does not exist (already removed)');
            }

            // Check and drop 'created_at' column
            if ($this->columnExists('created_at')) {
                $this->warn('Dropping column: created_at');
                DB::statement('ALTER TABLE dbo.GEO DROP COLUMN created_at');
                $this->info('✓ Column created_at dropped successfully');
            } else {
                $this->line('✓ Column created_at does not exist (already removed)');
            }

            // Check and drop 'updated_at' column
            if ($this->columnExists('updated_at')) {
                $this->warn('Dropping column: updated_at');
                DB::statement('ALTER TABLE dbo.GEO DROP COLUMN updated_at');
                $this->info('✓ Column updated_at dropped successfully');
            } else {
                $this->line('✓ Column updated_at does not exist (already removed)');
            }

            $this->info('');
            $this->info('Final table structure:');
            
            // Get final columns
            $finalColumns = DB::select("
                SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, IS_NULLABLE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = 'GEO'
                ORDER BY ORDINAL_POSITION
            ");
            
            foreach ($finalColumns as $column) {
                $length = $column->CHARACTER_MAXIMUM_LENGTH ?? '';
                $nullable = $column->IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
                $this->line("  - {$column->COLUMN_NAME} ({$column->DATA_TYPE}{$length}) {$nullable}");
            }
            
            $this->info('');
            $this->info('Expected columns: code, country, state, town, town_section, area');
            $this->info('');
            $this->info('✅ GEO table structure fixed successfully!');
            
            // Count records
            $count = DB::table('GEO')->count();
            $this->info("Total records in GEO table: {$count}");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error fixing GEO table structure:');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Check if a column exists in GEO table
     *
     * @param string $columnName
     * @return bool
     */
    private function columnExists($columnName)
    {
        $result = DB::select("
            SELECT COUNT(*) as count
            FROM sys.columns 
            WHERE object_id = OBJECT_ID(N'dbo.GEO') 
            AND name = ?
        ", [$columnName]);

        return $result[0]->count > 0;
    }
}
