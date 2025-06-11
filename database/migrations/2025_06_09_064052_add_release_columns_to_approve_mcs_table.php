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
        // Skip this migration since we've included these columns in the create table migration
        if (Schema::hasTable('approve_mcs')) {
            // Only add columns if they don't exist
            Schema::table('approve_mcs', function (Blueprint $table) {
                if (!Schema::hasColumn('approve_mcs', 'released_by')) {
                    $table->string('released_by')->nullable()->after('rejection_reason')->comment('User ID who released the MC');
                }
                
                if (!Schema::hasColumn('approve_mcs', 'released_date')) {
                    $table->timestamp('released_date')->nullable()->after('released_by')->comment('Date when MC was released');
                }
                
                if (!Schema::hasColumn('approve_mcs', 'release_notes')) {
                    $table->text('release_notes')->nullable()->after('released_date')->comment('Notes about the release');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('approve_mcs')) {
            Schema::table('approve_mcs', function (Blueprint $table) {
                $columns = ['released_by', 'released_date', 'release_notes'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('approve_mcs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}; 