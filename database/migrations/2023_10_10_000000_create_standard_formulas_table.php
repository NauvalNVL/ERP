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
        Schema::create('standard_formulas', function (Blueprint $table) {
            $table->id();
            $table->enum('activate_standard_formula', ['yes', 'no'])->default('yes');
            $table->enum('economic_run_size', ['average', 'highest'])->default('average');
            $table->boolean('check_run_size_result')->default(true);
            $table->enum('master_card', ['free', 'accept'])->default('free');
            $table->enum('sales_order', ['free', 'accept'])->default('free');
            $table->enum('work_order', ['free', 'accept'])->default('free');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_formulas');
    }
}; 