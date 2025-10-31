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
        // Skip - the salesperson table already has the correct structure
        // This migration was created to update an old table structure that no longer exists
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
