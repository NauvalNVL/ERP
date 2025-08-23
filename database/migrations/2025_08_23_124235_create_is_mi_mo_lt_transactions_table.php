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
        Schema::create('is_mi_mo_lt_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 50)->unique();
            $table->enum('transaction_type', ['IS', 'MI', 'MO', 'LT']);
            $table->string('sku_code', 20);
            $table->string('location_code', 20);
            $table->string('category_code', 20);
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->string('description', 500);
            $table->date('transaction_date');
            $table->string('reference_number', 50)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Draft', 'Posted', 'Cancelled'])->default('Draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('sku_code')->references('sku')->on('mm_skus');
            $table->foreign('location_code')->references('code')->on('mm_locations');
            $table->foreign('category_code')->references('code')->on('mm_categories');

            // Indexes
            $table->index(['transaction_type', 'status']);
            $table->index('transaction_date');
            $table->index('sku_code');
            $table->index('location_code');
            $table->index('category_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('is_mi_mo_lt_transactions');
    }
};
