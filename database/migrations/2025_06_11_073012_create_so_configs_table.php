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
        Schema::create('so_configs', function (Blueprint $table) {
            $table->id();
            $table->json('activate_order_type')->nullable();
            $table->string('check_roll_balance')->default('N-No Check');
            $table->json('roll_stock_balance_check')->nullable();
            $table->integer('compute_roll_stock_balance')->default(10);
            $table->integer('compute_wip_sales_orders')->default(10);
            $table->string('jit_so_po_no')->default('0');
            $table->json('new_so_config')->nullable();
            $table->json('amend_so_config')->nullable();
            $table->json('cancel_so_config')->nullable();
            $table->json('close_so_config')->nullable();
            $table->json('so_to_dms_config')->nullable();
            $table->json('so_to_wo_config')->nullable();
            $table->json('others_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_configs');
    }
};
