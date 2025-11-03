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
        Schema::create('customer_tax_product_tieups', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50);
            $table->integer('index_number');
            $table->string('product_group_code', 20);
            $table->boolean('tie_up_enabled')->default(false);
            $table->timestamps();
            
            // Composite unique key
            $table->unique(['customer_code', 'index_number', 'product_group_code'], 'cust_idx_pg_unique');
            
            // Indexes
            $table->index(['customer_code', 'index_number']);
            $table->index('product_group_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_tax_product_tieups');
    }
};
