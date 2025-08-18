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
        Schema::create('sku_prices', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code', 20);
            $table->decimal('price', 15, 2);
            $table->string('currency_code', 3)->default('IDR');
            $table->date('effective_date');
            $table->boolean('is_active')->default(true);
            $table->string('price_type', 10)->default('S'); // S-SKU, P-P/Order
            $table->string('po_status', 1)->nullable(); // 0-Outstanding, P-Partial, C-Completed, M-Manual, X-Cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['sku_code', 'effective_date']);
            $table->index(['sku_code', 'is_active']);
            $table->index('currency_code');
            $table->index('price_type');
            $table->index('po_status');

            // Foreign key constraint
            $table->foreign('sku_code')->references('sku')->on('mm_skus')->onDelete('cascade');
            $table->foreign('currency_code')->references('currency_code')->on('foreign_currencies')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_prices');
    }
};
