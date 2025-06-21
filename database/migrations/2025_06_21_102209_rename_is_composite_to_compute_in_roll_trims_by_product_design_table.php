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
        Schema::table('roll_trims_by_product_design', function (Blueprint $table) {
            $table->renameColumn('is_composite', 'compute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roll_trims_by_product_design', function (Blueprint $table) {
            $table->renameColumn('compute', 'is_composite');
        });
    }
};
