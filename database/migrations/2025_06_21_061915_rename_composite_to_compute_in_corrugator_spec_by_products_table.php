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
        Schema::table('corrugator_spec_by_products', function (Blueprint $table) {
            if (Schema::hasColumn('corrugator_spec_by_products', 'composite')) {
                $table->renameColumn('composite', 'compute');
            } else if (!Schema::hasColumn('corrugator_spec_by_products', 'compute')) {
                $table->boolean('compute')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('corrugator_spec_by_products', function (Blueprint $table) {
            if (Schema::hasColumn('corrugator_spec_by_products', 'compute')) {
                $table->renameColumn('compute', 'composite');
            }
        });
    }
};
