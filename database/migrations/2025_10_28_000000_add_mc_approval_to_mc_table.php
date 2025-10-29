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
        Schema::table('MC', function (Blueprint $table) {
            // Add mc_approval column if it doesn't exist
            if (!Schema::hasColumn('MC', 'mc_approval')) {
                $table->string('mc_approval', 10)->nullable()->default('No')->after('STS');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MC', function (Blueprint $table) {
            if (Schema::hasColumn('MC', 'mc_approval')) {
                $table->dropColumn('mc_approval');
            }
        });
    }
};
