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
        Schema::create('rc_rt_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 50)->unique();
            $table->enum('transaction_type', ['RC', 'RT']); // RC = Receipt, RT = Return
            $table->date('transaction_date');
            $table->string('supplier_code', 20);
            $table->string('supplier_name', 100);
            $table->string('po_number', 50)->nullable();
            $table->string('sku_code', 20);
            $table->string('sku_name', 100);
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->string('location_code', 20);
            $table->string('location_name', 100);
            $table->enum('status', ['Draft', 'Posted', 'Cancelled'])->default('Draft');
            $table->text('remarks')->nullable();
            $table->string('created_by', 50);
            $table->string('approved_by', 50)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('posted_by', 50)->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->string('cancelled_by', 50)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['transaction_type', 'status']);
            $table->index(['supplier_code']);
            $table->index(['sku_code']);
            $table->index(['location_code']);
            $table->index(['transaction_date']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rc_rt_transactions');
    }
};
