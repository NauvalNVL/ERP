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
        Schema::create('sales_board_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('so_number')->unique();
            $table->integer('current_period');
            $table->integer('update_period');
            $table->integer('forward_period')->default(1);
            $table->integer('backward_period')->default(1);
            
            // Customer Information
            $table->string('customer_code');
            $table->string('salesperson_code');
            $table->string('customer_p_order_number')->nullable();
            $table->date('p_order_date')->nullable();
            
            // Product Information
            $table->string('product_design')->nullable();
            $table->string('flute')->nullable();
            $table->string('so_b_quality')->nullable();
            $table->string('wo_b_quality')->nullable();
            
            // Size Information
            $table->string('board_size_length')->nullable();
            $table->string('board_size_width')->nullable();
            $table->string('sheet_size_length')->nullable();
            $table->string('sheet_size_width')->nullable();
            $table->string('paper_size')->nullable();
            
            // Tooling and Conversion
            $table->string('s_tool')->nullable();
            $table->string('corr_out')->nullable();
            $table->string('conv_out')->nullable();
            $table->decimal('area_per_pcs', 10, 4)->default(0);
            $table->decimal('l_meter', 10, 2)->default(0);
            
            // Order Details
            $table->integer('order_quantity')->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->decimal('exchange_rate', 10, 4)->default(0);
            $table->string('lot_number')->nullable();
            $table->integer('pcs_per_bundle')->nullable();
            $table->boolean('sales_tax')->default(false);
            
            // Order Configuration
            $table->enum('order_group', ['Sales', 'Non-Sales'])->default('Sales');
            $table->string('order_type')->nullable();
            
            // Additional Information
            $table->text('remark')->nullable();
            $table->text('instruction_1')->nullable();
            $table->text('instruction_2')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 12, 4)->default(0);
            
            // Status and Audit
            $table->enum('status', ['Draft', 'Active', 'Completed', 'Cancelled'])->default('Draft');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['customer_code', 'status']);
            $table->index(['salesperson_code', 'status']);
            $table->index(['current_period', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_board_sales_orders');
    }
};
