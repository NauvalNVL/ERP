<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safely drop FK by name if it exists (SQL Server)
        DB::statement("
IF EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'salesperson_sales_team_id_foreign')
    ALTER TABLE salesperson DROP CONSTRAINT salesperson_sales_team_id_foreign;
");
        
        Schema::table('salesperson', function (Blueprint $table) {
            // Drop existing columns that will be replaced (guard each)
            foreach (['sales_team_id', 'position', 'user_id', 'is_active'] as $col) {
                if (Schema::hasColumn('salesperson', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Modify existing columns if present
            if (Schema::hasColumn('salesperson', 'code')) {
                $table->string('code', 50)->nullable()->change();
            }
            if (Schema::hasColumn('salesperson', 'name')) {
                $table->string('name', 50)->nullable()->change();
            }

            // Add new columns (only if not already there)
            if (!Schema::hasColumn('salesperson', 'Grup')) {
                $table->string('Grup', 20)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            }
            if (!Schema::hasColumn('salesperson', 'CodeGrup')) {
                $table->string('CodeGrup', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            }
            if (!Schema::hasColumn('salesperson', 'TargetSales')) {
                $table->decimal('TargetSales', 18, 2)->nullable();
            }
            if (!Schema::hasColumn('salesperson', 'Internal')) {
                $table->string('Internal', 20)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            }
            if (!Schema::hasColumn('salesperson', 'Email')) {
                $table->string('Email', 100)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            }
            if (!Schema::hasColumn('salesperson', 'status')) {
                $table->char('status', 10)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            }
        });
        
        // Rename columns to match the specification
        Schema::table('salesperson', function (Blueprint $table) {
            if (Schema::hasColumn('salesperson', 'code')) {
                $table->renameColumn('code', 'Code');
            }
            if (Schema::hasColumn('salesperson', 'name')) {
                $table->renameColumn('name', 'Name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salesperson', function (Blueprint $table) {
            // Rename columns back
            $table->renameColumn('Code', 'code');
            $table->renameColumn('Name', 'name');
            
            // Drop new columns
            $table->dropColumn(['Grup', 'CodeGrup', 'TargetSales', 'Internal', 'Email', 'status']);
            
            // Restore original columns
            $table->string('code', 10)->unique()->change();
            $table->string('name', 100)->change();
            $table->foreignId('sales_team_id')->nullable()->constrained('sales_team');
            $table->string('position', 50)->nullable();
            $table->string('user_id', 20)->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};
