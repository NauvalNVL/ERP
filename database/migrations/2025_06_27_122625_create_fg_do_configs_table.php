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
        Schema::create('fg_do_configs', function (Blueprint $table) {
            $table->id();
            $table->string('lock_customer_location')->default('N-No');
            $table->string('whse_location_normal')->nullable();
            $table->string('whse_location_excess')->nullable();
            $table->string('whse_location_transit')->nullable();
            $table->string('stock_in_normal')->default('SI01');
            $table->string('stock_in_booking')->default('SI01');
            $table->string('stock_out_normal')->default('SO01');
            $table->string('stock_out_booking')->default('SO01');
            $table->string('warehouse_transfer')->default('WT01');
            $table->string('print_stock_in')->default('N-No');
            $table->string('print_stock_out')->default('N-No');
            $table->string('print_w_transfer')->default('N-No');
            $table->integer('keep_fg_records_for')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fg_do_configs');
    }
};
