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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number')->unique();
            $table->string('so_number');
            $table->string('customer_name');
            $table->decimal('plan_qty', 15, 2);
            $table->date('due_date');
            $table->enum('status', ['Active', 'Completed', 'Cancelled', 'Planned'])->default('Active');
            $table->enum('wo_status', ['Active', 'Completed', 'Cancelled', 'Planned'])->default('Active');
            $table->enum('so_status', ['Outstanding', 'Completed', 'Cancelled'])->default('Outstanding');
            $table->string('acr_number')->nullable();
            $table->string('mcs_number')->nullable();
            $table->string('model')->nullable();
            $table->string('comp_number')->nullable();
            $table->string('part_number')->nullable();
            $table->string('pd')->nullable();
            $table->string('pcs_per_unit')->nullable();
            $table->string('ied_x')->default('0');
            $table->string('ied_y')->default('0');
            $table->decimal('completed_qty', 15, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->text('description')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
