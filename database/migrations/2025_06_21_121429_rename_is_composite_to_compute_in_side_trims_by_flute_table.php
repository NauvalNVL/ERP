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
        Schema::table('side_trims_by_flute', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique('side_trim_flute_unique');
            
            // Rename the column
            $table->renameColumn('is_composite', 'compute');
        });
        
        // Add the new unique constraint with updated column name
        Schema::table('side_trims_by_flute', function (Blueprint $table) {
            $table->unique(['flute_id', 'compute'], 'side_trim_flute_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('side_trims_by_flute', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('side_trim_flute_unique');
            
            // Rename the column back
            $table->renameColumn('compute', 'is_composite');
        });
        
        // Add back the old unique constraint
        Schema::table('side_trims_by_flute', function (Blueprint $table) {
            $table->unique(['flute_id', 'is_composite'], 'side_trim_flute_unique');
        });
    }
};
