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
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->string('type'); // e.g., '1-Normal Stock', '2-Excess Stock', '3-Excess Stock in Transit'
            $table->boolean('to_lock_delivery_order')->default(false);
            $table->boolean('to_lock_stock_out_adjustment')->default(false);
            $table->boolean('to_lock_warehouse_transfer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_locations');
    }
}; 