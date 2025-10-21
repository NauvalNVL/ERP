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
        Schema::create('pr_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id');
            $table->integer('line_number')->default(1);
            $table->string('sku_code', 20)->nullable();
            $table->text('description');
            $table->text('specification')->nullable();
            $table->decimal('quantity', 15, 3);
            $table->string('unit', 20)->nullable();
            $table->decimal('estimated_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            
            // Stock information
            $table->decimal('current_stock', 15, 3)->nullable();
            $table->enum('stock_status', [
                'available', 
                'low', 
                'out_of_stock', 
                'not_found', 
                'unknown', 
                'error'
            ])->default('unknown');
            
            // Item priority/urgency
            $table->enum('urgency', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->default('MEDIUM');
            $table->text('notes')->nullable();
            
            // Approval and conversion tracking
            $table->decimal('approved_quantity', 15, 3)->nullable();
            $table->decimal('remaining_quantity', 15, 3)->default(0);
            $table->decimal('converted_quantity', 15, 3)->default(0);
            
            // Audit fields - Reference to USERCPS.ID
            $table->string('created_by', 20)->nullable();
            $table->string('updated_by', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('pr_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');
            // Note: Foreign key to mm_skus table removed as it may not exist in all environments

            // Indexes
            $table->index(['pr_id']);
            $table->index(['sku_code']);
            $table->index(['stock_status']);
            $table->index(['urgency']);
            $table->index(['line_number']);
            $table->unique(['pr_id', 'line_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_items');
    }
};
