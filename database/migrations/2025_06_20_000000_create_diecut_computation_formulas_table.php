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
        // Check if the table already exists to avoid errors
        if (!Schema::hasTable('diecut_computation_formulas')) {
            Schema::create('diecut_computation_formulas', function (Blueprint $table) {
                $table->id();
                $table->integer('board_length_min')->default(0);
                $table->integer('board_length_max')->default(999999);
                $table->integer('board_width_min')->default(0);
                $table->integer('board_width_max')->default(999999);
                $table->integer('dc_sheet_length_min')->default(1);
                $table->integer('dc_sheet_length_max')->default(999999);
                $table->integer('dc_sheet_width_min')->default(1);
                $table->integer('dc_sheet_width_max')->default(999999);
                $table->integer('no_of_up_min')->default(1);
                $table->integer('no_of_up_max')->default(99);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We're not dropping the table as it might be used by other migrations
        // Schema::dropIfExists('diecut_computation_formulas');
    }
}; 