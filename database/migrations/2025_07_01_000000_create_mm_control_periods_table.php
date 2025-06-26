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
        Schema::create('mm_control_periods', function (Blueprint $table) {
            $table->id();
            
            // P/Requisition settings
            $table->string('pr_forward_period')->default('0');
            $table->string('pr_control_date')->default('1');
            
            // P/Order settings
            $table->integer('po_current_period_month')->default(now()->month);
            $table->integer('po_current_period_year')->default(now()->year);
            $table->string('po_forward_period')->default('1');
            $table->string('po_control_date')->default('1');
            $table->decimal('po_min_allow_percentage', 5, 2)->default(0.00);
            $table->decimal('po_max_allow_percentage', 5, 2)->default(0.00);
            $table->enum('po_zero_po', ['Y', 'N'])->default('N');
            
            // Inventory settings
            $table->integer('inv_current_period_month')->default(now()->month);
            $table->integer('inv_current_period_year')->default(now()->year);
            $table->string('inv_backward_period')->default('0');
            $table->string('inv_control_date')->default('1');
            $table->enum('inv_zero_tran', ['Y', 'N'])->default('Y');
            
            // Costing settings
            $table->integer('cost_current_period_month')->default(now()->month);
            $table->integer('cost_current_period_year')->default(now()->year);
            $table->string('cost_control_date')->default('1');
            $table->boolean('cost_y_allow_after_period')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_control_periods');
    }
}; 