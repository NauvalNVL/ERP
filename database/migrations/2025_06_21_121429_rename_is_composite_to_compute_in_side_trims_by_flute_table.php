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
        // First, check if the column exists
        if (Schema::hasColumn('side_trims_by_flute', 'is_composite')) {
            // Drop the unique index first
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->dropUnique('side_trim_flute_unique');
            });

            // Add the new column
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->boolean('compute')->nullable();
            });

            // Copy data from old column to new column
            DB::statement('UPDATE side_trims_by_flute SET [compute] = [is_composite]');

            // Drop the old column
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->dropColumn('is_composite');
            });

            // Recreate the unique index with the new column name
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->unique(['flute_id', 'compute'], 'side_trim_flute_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, check if the column exists
        if (Schema::hasColumn('side_trims_by_flute', 'compute')) {
            // Drop the unique index first
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->dropUnique('side_trim_flute_unique');
            });

            // Add the old column back
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->boolean('is_composite')->nullable();
            });

            // Copy data from new column to old column
            DB::statement('UPDATE side_trims_by_flute SET [is_composite] = [compute]');

            // Drop the new column
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->dropColumn('compute');
            });

            // Recreate the unique index with the original column name
            Schema::table('side_trims_by_flute', function (Blueprint $table) {
                $table->unique(['flute_id', 'is_composite'], 'side_trim_flute_unique');
            });
        }
    }
};
