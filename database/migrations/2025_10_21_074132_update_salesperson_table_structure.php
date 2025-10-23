<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salesperson', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['sales_team_id']);
        });
        
        Schema::table('salesperson', function (Blueprint $table) {
            // Drop existing columns that will be replaced
            $table->dropColumn(['sales_team_id', 'position', 'user_id', 'is_active']);
            
            // Modify existing columns
            $table->string('code', 50)->nullable()->change();
            $table->string('name', 50)->nullable()->change();
            
            // Add new columns
            $table->string('Grup', 20)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('CodeGrup', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->decimal('TargetSales', 18, 2)->nullable();
            $table->string('Internal', 20)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('Email', 100)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->char('status', 10)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
        });
        
        // Rename columns to match the specification
        Schema::table('salesperson', function (Blueprint $table) {
            $table->renameColumn('code', 'Code');
            $table->renameColumn('name', 'Name');
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
