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
        Schema::create('customer_warehouse_requirements', function (Blueprint $table) {
            $table->string('customer_code')->primary();
            $table->string('customer_name');
            $table->string('default_warehouse_normal')->nullable();
            $table->string('default_warehouse_excess')->nullable();
            $table->string('default_warehouse_transit')->nullable();
            $table->string('delivery_order_format')->nullable();
            $table->string('bar_code_sticker')->default('N-N/Applicable');
            $table->string('bundle_format')->default('0');
            $table->boolean('destination_change')->default(false);
            $table->boolean('multi_line_quantity')->default(false);
            $table->boolean('product_group_tie_up')->default(false);
            $table->boolean('unapplied_fg_goods')->default(false);
            $table->boolean('amend_do_un_qty')->default(false);
            $table->timestamps();
            
            // Remove foreign key constraints for now
            // $table->foreign('customer_code')->references('customer_code')->on('update_customer_accounts')->onDelete('cascade');
            // $table->foreign('delivery_order_format')->references('code')->on('delivery_order_formats')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_warehouse_requirements');
    }
}; 