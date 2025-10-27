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
        Schema::table('master_cards', function (Blueprint $table) {
            // Check if customer_code column doesn't exist, then add it
            if (!Schema::hasColumn('master_cards', 'customer_code')) {
                $table->string('customer_code', 20)->nullable()->after('mc_seq');
                
                // Add index for better performance (without foreign key for now)
                $table->index('customer_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_cards', function (Blueprint $table) {
            if (Schema::hasColumn('master_cards', 'customer_code')) {
                // Drop index
                $table->dropIndex(['customer_code']);
                
                // Drop column
                $table->dropColumn('customer_code');
            }
        });
    }
};
