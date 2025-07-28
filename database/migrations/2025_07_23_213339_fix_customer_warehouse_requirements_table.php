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
        Schema::table('customer_warehouse_requirements', function (Blueprint $table) {
            // Add new fields for DO Form
            $table->string('closed_sales_order_delivery')->default('N-No delivery allowed');
            $table->string('completed_sales_order_delivery')->default('N-No delivery allowed');
            $table->string('outstanding_partial_delivery')->default('D-Reject if over-delivered with allowance');
            
            // Add new fields for DO QTY and Reject QTY
            $table->integer('allow_qty')->default(0);
            $table->string('allow_type')->default('1-Absolute Quantity');
            $table->string('less_from_invoice')->default('Y-Billable Qty = D/O Qty - Rejection Qty');
            
            // Default number of copies
            $table->integer('default_copies')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_warehouse_requirements', function (Blueprint $table) {
            $table->dropColumn([
                'closed_sales_order_delivery',
                'completed_sales_order_delivery',
                'outstanding_partial_delivery',
                'allow_qty',
                'allow_type',
                'less_from_invoice',
                'default_copies'
            ]);
        });
    }
};
