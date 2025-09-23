<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('so_number')->unique();

            // Customer snapshot
            $table->string('customer_code');
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('credit_terms')->nullable();
            $table->decimal('credit_limit', 18, 2)->nullable();
            $table->string('salesperson_code')->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->decimal('exchange_rate', 18, 6)->default(0);

            // Master Card snapshot
            $table->string('master_card_seq');
            $table->string('master_card_model')->nullable();
            $table->string('p_design')->nullable();
            $table->string('uom')->nullable();

            // Order fields
            $table->string('order_mode')->nullable();
            $table->string('product_code')->nullable();
            $table->string('order_group')->nullable();
            $table->string('order_type')->nullable();
            $table->boolean('sales_tax')->default(false);
            $table->string('lot_number')->nullable();
            $table->string('customer_po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->text('remark')->nullable();
            $table->text('instruction1')->nullable();
            $table->text('instruction2')->nullable();

            // Status workflow
            $table->string('status')->default('Draft');

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['customer_code']);
            $table->index(['master_card_seq']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};


