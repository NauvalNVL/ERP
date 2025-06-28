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
        Schema::table('side_trims_by_product_design', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique('side_trim_product_design_unique');
            
            // Check if is_composite column exists before renaming
            if (Schema::hasColumn('side_trims_by_product_design', 'is_composite')) {
                $table->renameColumn('is_composite', 'compute');
            }
        });
        
        // Add the new unique constraint with updated column name
        Schema::table('side_trims_by_product_design', function (Blueprint $table) {
            $table->unique(['product_design_id', 'product_id', 'flute_id'], 'side_trim_product_design_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('side_trims_by_product_design', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('side_trim_product_design_unique');
            
            // Check if compute column exists before renaming back
            if (Schema::hasColumn('side_trims_by_product_design', 'compute')) {
                $table->renameColumn('compute', 'is_composite');
            }
        });
        
        // Add back the old unique constraint
        Schema::table('side_trims_by_product_design', function (Blueprint $table) {
            $table->unique(['product_design_id', 'product_id', 'flute_id'], 'side_trim_product_design_unique');
        });
    }
}; 