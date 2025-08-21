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
        Schema::create('fg_stock_in_by_wo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->string('wo_number');
            $table->string('entry_ref');
            $table->decimal('entry_qty', 15, 2);
            $table->date('entry_date');
            $table->string('warehouse');
            $table->string('analysis')->nullable();
            $table->text('remark')->nullable();
            $table->string('period')->default('05/2025');
            $table->string('created_by')->nullable();
            $table->enum('status', ['Pending', 'Processed', 'Cancelled'])->default('Processed');
            $table->timestamps();
            
            $table->index(['wo_number', 'entry_date']);
            $table->index(['warehouse', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fg_stock_in_by_wo');
    }
};
