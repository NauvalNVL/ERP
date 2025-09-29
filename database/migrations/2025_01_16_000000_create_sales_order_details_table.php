<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('so_number');
            $table->integer('line_number');
            $table->string('item_code');
            $table->string('item_description')->nullable();
            $table->decimal('order_quantity', 15, 2);
            $table->decimal('unit_price', 18, 4);
            $table->decimal('line_total', 18, 2);
            $table->string('uom')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('so_number')->references('so_number')->on('sales_orders')->onDelete('cascade');
            
            // Indexes
            $table->index(['so_number', 'line_number']);
            $table->unique(['so_number', 'line_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_details');
    }
};
